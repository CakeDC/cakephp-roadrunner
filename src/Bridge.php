<?php
declare(strict_types=1);

namespace CakeDC\Roadrunner;

use Cake\Core\HttpApplicationInterface;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Http\Runner;
use Cake\Http\Server;
use Cake\Http\ServerRequest;
use CakeDC\Roadrunner\Http\ServerRequestFactory;
use CakeDC\Roadrunner\Exception\CakeRoadrunnerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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
     * @param \Psr\Http\Message\ServerRequestInterface $request PSR Server Request Interface
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
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

    private static function buildHostHeaderFromUri(UriInterface $uri): string
    {
        $shouldIncludePort = ($uri->getScheme() === 'http' && $uri->getPort() !== 80)
            || ($uri->getScheme() === 'https' && !$uri->getPort() !== 443);

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
     * @param \Psr\Http\Message\ServerRequestInterface $request An instance of ServerRequestInterface
     * @return \Cake\Http\ServerRequest
     */
    public static function convertRequest(ServerRequestInterface $request): ServerRequest
    {
        $cakeRequest = ServerRequestFactory::fromGlobals(
            $request->getServerParams(),
            $request->getQueryParams(),
            $request->getParsedBody(),
            $request->getCookieParams(),
            $request->getUploadedFiles()
        );
        $cakeRequest->trustProxy = true;

        // Add the Host header and the HTTP_HOST environment variable to the request.
        // Those are not added on the request that comes from Roadrunner, so we derive
        // it from the host in the URI.
        $host = static::buildHostHeaderFromUri($request->getUri());
        $cakeRequest = $cakeRequest
            ->withEnv('HTTP_HOST', $host)
            ->withHeader('Host', $host);

        $request->getBody()->rewind();

        return clone $cakeRequest->withBody($request->getBody());
    }
}
