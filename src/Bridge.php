<?php
declare(strict_types=1);

namespace CakeDC\Roadrunner;

use Cake\Core\HttpApplicationInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Runner;
use Cake\Http\Server;
use Cake\Http\ServerRequest as CakeServerRequest;
use CakeDC\Roadrunner\Exception\CakeRoadrunnerException;
use CakeDC\Roadrunner\Http\ServerRequestFactory;
use Laminas\Diactoros\ServerRequest as LaminasServerRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * The CakePHP RoadRunner Bridge converts a request to a PSR response suitable for the RoadRunner server. This should
 * be called from your worker file.
 *
 * Example:
 *
 * ```php
 * # worker/cakephp-worker.php
 * $bridge = new Bridge(__DIR__);
 * ```
 *
 * When a request is ready to be handled in your worker:
 *
 * ```php
 * return $bridge->handle($request);
 * ```
 *
 * @link https://roadrunner.dev/docs/php-worker
 */
class Bridge
{
    /**
     * @param string $rootDir Absolute path to your applications root directory without the trailing slash. For example,
     *      if your `composer.json` file is located at `/srv/app/composer.json` then `/srv/app` is your $rootDir.
     * @param \Cake\Core\HttpApplicationInterface|\Cake\Core\PluginApplicationInterface|null $application Application
     * (e.g. `\App\Application`), if null then the constructor will attempt creating an instance.
     * @param \Cake\Http\Server|null $server Server instance, if null then one will be created for you.
     */
    public function __construct(
        private string $rootDir,
        private HttpApplicationInterface|PluginApplicationInterface|null $application = null,
        private ?Server $server = null
    ) {
        if (str_ends_with($this->rootDir, '/')) {
            $this->rootDir = substr($this->rootDir, 0, -1);
        }
        if (!file_exists($this->rootDir)) {
            throw new CakeRoadrunnerException(
                sprintf(
                    CakeRoadrunnerException::ROOT_DIR_NOT_FOUND,
                    $this->rootDir
                )
            );
        }

        $configDir = "$this->rootDir/config";

        if ($this->application == null && class_exists('\App\Application')) {
            $this->application = new \App\Application($configDir);
        } else {
            throw new CakeRoadrunnerException(CakeRoadrunnerException::APP_INSTANCE_NOT_CREATED);
        }

        $this->server = $server ?? new Server($this->application);

        require $configDir . '/requirements.php';
        $this->application->bootstrap();
        if ($this->application instanceof PluginApplicationInterface) {
            $this->application->pluginBootstrap();
        }
    }

    /**
     * Handle the request and return a response.
     *
     * @param \Laminas\Diactoros\ServerRequest $request Server Request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(LaminasServerRequest $request): ResponseInterface
    {
        $request = static::convertRequest($request);
        $middleware = $this->application->middleware(new MiddlewareQueue());
        if ($this->application instanceof PluginApplicationInterface) {
            $middleware = $this->application->pluginMiddleware($middleware);
        }

        $this->server->dispatchEvent('Server.buildMiddleware', ['middleware' => $middleware]);
        //$middleware->add($this->application);
        /** @var \Cake\Http\Response $response */
        $response = (new Runner())->run($middleware, $request, $this->application);

        $cookies = [];
        foreach ($response->getCookieCollection() as $cookie) {
            if (!$cookie->getExpiresTimestamp()) {
                $cookie = $cookie->withNeverExpire();
            }
            $cookies[] = $cookie->toHeaderValue();
        }
        if (!empty($cookies)) {
            $response = $response->withAddedHeader('Set-Cookie', $cookies);
        }

        session_write_close();

        return $response;
    }

    /**
     * Generates a host header, based upon the contents from the URI.
     *
     * @param \Psr\Http\Message\UriInterface $uri The request's URI
     * @return string The generated Host header.
     */
    protected static function buildHostHeaderFromUri(UriInterface $uri): string
    {
        $uriPort = $uri->getPort();
        if ($uriPort === null) {
            return $uri->getHost();
        }

        $shouldIncludePort = ($uri->getScheme() === 'http' && $uriPort !== 80)
            || ($uri->getScheme() === 'https' && $uriPort !== 443);

        if ($shouldIncludePort) {
            return "{$uri->getHost()}:{$uri->getPort()}";
        } else {
            return $uri->getHost();
        }
    }

    /**
     * Convert ServerRequestInterface to Cake ServerRequest. This is necessary since some CakePHP internals and some
     * plugin middleware require an instance of Cake ServerRequest.
     *
     * @todo result of `$request->getParsedBody()` is always null, see link tag below this todo. We rely on
     *      BodyParserMiddleware after setting the body on the ServerRequest below.
     * @link https://github.com/roadrunner-server/roadrunner/discussions/953
     * @param \Laminas\Diactoros\ServerRequest $request An instance of Laminas's ServerRequest
     * @return \Cake\Http\ServerRequest
     */
    public static function convertRequest(LaminasServerRequest $request): CakeServerRequest
    {
        // Add the Host header and the HTTP_HOST environment variable to the request.
        // Those are not added on the request that comes from Roadrunner, so we derive
        // it from the host in the URI.
        $host = static::buildHostHeaderFromUri($request->getUri());
        $serverParams = $request->getServerParams() + [
            'HTTP_HOST' => $host,
        ];

        $cakeRequest = ServerRequestFactory::fromGlobals(
            $serverParams,
            $request->getQueryParams(),
            $request->getParsedBody(),
            $request->getCookieParams(),
            $request->getUploadedFiles()
        );
        $cakeRequest->trustProxy = true;

        $cakeRequest = static::copyHeadersFromRoadrunnerRequest($cakeRequest, $request);
        $cakeRequest = static::parseBasicAuthenticationIntoRequestEnvironment($cakeRequest);
        $cakeRequest = $cakeRequest->withUri($request->getUri());

        $request->getBody()->rewind();

        return clone $cakeRequest->withBody($request->getBody());
    }

    /**
     * Copies the headers from the original request (originated from Roadrunner) to our
     * converted request. This is needed because, otherwise, headers are parsed from the `$request->getServerParams()`
     * contents, and in those duplicated headers are concatenated into a single comma-separated one.
     *
     * @param \Cake\Http\ServerRequest $convertedRequest Our converted request
     * @param \Laminas\Diactoros\ServerRequest $roadrunnerRequest The original request
     * @return \Cake\Http\ServerRequest
     */
    protected static function copyHeadersFromRoadrunnerRequest(
        CakeServerRequest $convertedRequest,
        LaminasServerRequest $roadrunnerRequest
    ): CakeServerRequest {
        foreach ($roadrunnerRequest->getHeaders() as $headerName => $headerValue) {
            // This is needed because internally CakePHP stores headers in the same place as
            // the request's environment variables, and when we call `withHeader` with an array parameter
            // this environment variable contents (retrieved by calling `$request->getEnv()`) change from
            // a string to an array.
            if (count($headerValue) === 1) {
                $convertedRequest = $convertedRequest->withHeader($headerName, $headerValue[0]);
            } else {
                $convertedRequest = $convertedRequest->withHeader($headerName, $headerValue);
            }
        }

        return $convertedRequest;
    }

    /**
     * If they're present, parses basic authentication info from the request headers into the
     * `PHP_AUTH_USER` and `PHP_AUTH_PW` request environment variables.
     *
     * @param \Cake\Http\ServerRequest $request The request with the data to be parsed
     * @return \Cake\Http\ServerRequest
     */
    protected static function parseBasicAuthenticationIntoRequestEnvironment(
        CakeServerRequest $request
    ): CakeServerRequest {
        $authorizationHeader = $request->getHeader('Authorization')[0] ?? null;
        if ($authorizationHeader === null) {
            return $request;
        }

        $matches = [];
        $matched = preg_match('/Basic\s+(.*)$/i', $authorizationHeader, $matches);
        if (!$matched) {
            return $request;
        }

        $decodedParameter = base64_decode($matches[1], true);
        if (!$decodedParameter) {
            return $request;
        }

        $parts = explode(':', $decodedParameter, 2);

        return $request
            ->withEnv('PHP_AUTH_USER', $parts[0])
            ->withEnv('PHP_AUTH_PW', $parts[1] ?? '');
    }
}
