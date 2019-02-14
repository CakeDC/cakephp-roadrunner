<?php
namespace CakeDC\Roadrunner;

use Cake\Http\Cookie\Cookie;
use CakeDC\Roadrunner\Http\ServerRequestFactory;

class Bridge
{
    /**
     * @var Server
     */
    protected $server;

    /**
     * @var string root path
     */
    protected $root;

    public function __construct($root = null)
    {
        $this->root = $root;
        if ($root === null) {
            $this->root = dirname(__DIR__, 4);
        }
    }

    /**
     * Bootstrap an application
     *
     * @param string|null $appBootstrap The environment your application will use to bootstrap (if any)
     * @param string $appenv
     * @param bool $debug If debug is enabled
     */
    public function bootstrap($appBootstrap, $appenv, $debug)
    {
        require $this->root . '/config/requirements.php';
        require $this->root . '/vendor/autoload.php';
        $this->application = new \App\Application($this->root . '/config');
        $this->application->bootstrap();

        if ($this->application instanceof \Cake\Core\PluginApplicationInterface) {
            $this->application->pluginBootstrap();
        }
        $this->server = new \Cake\Http\Server($this->application);
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(\Psr\Http\Message\ServerRequestInterface $request)
    {
        $response = new \Cake\Http\Response();
        $request = $this->convertRequest($request);
        $middleware = $this->application->middleware(new \Cake\Http\MiddlewareQueue());
        if ($this->application instanceof \Cake\Core\PluginApplicationInterface) {
            $middleware = $this->application->pluginMiddleware($middleware);
        }

        if (!($middleware instanceof \Cake\Http\MiddlewareQueue)) {
            throw new \RuntimeException('The application `middleware` method did not return a middleware queue.');
        }
        $this->server->dispatchEvent('Server.buildMiddleware', ['middleware' => $middleware]);
        $middleware->add($this->application);
        $runner = new \Cake\Http\Runner();
        $response = $runner->run($middleware, $request, $response);
        $cookies = [];
        foreach ($response->getCookieCollection() as $cookie) {
            /**
             * @var Cookie $cookie
             */
            if ($cookie->getExpiresTimestamp() === '0') {
                $cookie = $cookie->withNeverExpire();
            }
            $cookies[] = $cookie->toHeaderValue();
        }
        if (!empty($cookies)) {
            $response = $response->withHeader('Set-Cookie', $cookies);
        }
        if (!($response instanceof \Psr\Http\Message\ResponseInterface)) {
            throw new \RuntimeException(sprintf(
                'Application did not create a response. Got "%s" instead.',
                is_object($response) ? get_class($response) : $response
            ));
        }

        session_write_close();

        return $response;
    }

    protected function convertRequest(\Zend\Diactoros\ServerRequest $request) : \Cake\Http\ServerRequest
    {
        $server = $request->getServerParams();
        $server['REQUEST_TIME'] = time();
        $server['REQUEST_TIME_FLOAT'] = microtime(true);
        $server['REMOTE_ADDR'] = '127.0.0.1';
        $server['SERVER_PROTOCOL'] = $request->getUri()->getScheme();
        $server['REQUEST_METHOD'] = $request->getMethod();
        $server['SERVER_NAME'] = $request->getUri()->getHost();
        $server['SERVER_PORT'] = $request->getUri()->getPort();
        $server['REQUEST_URI'] = $request->getUri()->getPath();

        $query = $request->getQueryParams();
        $body = $request->getParsedBody();
        $cookies = $request->getCookieParams();
        $files = $request->getUploadedFiles();

        $cakeRequest = ServerRequestFactory::fromGlobals($server, $query, $body, $cookies, $files);
        $cakeRequest->trustProxy = true;

        return $cakeRequest;
    }
}
