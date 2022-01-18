<?php
namespace CakeDC\Roadrunner\Http;

use Cake\Core\Configure;
use Cake\Http\ServerRequest;
use Cake\Http\ServerRequestFactory as BaseServerRequestFactory;
use function Laminas\Diactoros\normalizeServer;

/**
 * Creates an instance of ServerRequest.
 */
class ServerRequestFactory extends BaseServerRequestFactory
{
    /**
     * Override and inject a custom Session instance.
     *
     * @param array|null $server PHP Server environment variables, if null $_SERVER will be used.
     * @param array|null $query HTTP Query Parameters, if null $_GET will be used.
     * @param array|null $body HTTP Post Body, if null $_POST will be used.
     * @param array|null $cookies HTTP Cookies, if null $_COOKIE will be used.
     * @param array|null $files HTTP Files, if null $_FILES will be used.
     * @return \Cake\Http\ServerRequest
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ): ServerRequest {
        $server = normalizeServer($server ?: $_SERVER);
        $uri = static::createUri($server);
        $sessionConfig = (array)Configure::read('Session') + [
                'defaults' => 'php',
                'cookiePath' => $uri->webroot
            ];
        $session = Session::create($sessionConfig);
        $request = new ServerRequest([
            'environment' => $server,
            'uri' => $uri,
            'files' => $files ?: $_FILES,
            'cookies' => $cookies ?: $_COOKIE,
            'query' => $query ?: $_GET,
            'post' => $body ?: $_POST,
            'webroot' => $uri->webroot,
            'base' => $uri->base,
            'session' => $session,
        ]);

        return $request;
    }
}
