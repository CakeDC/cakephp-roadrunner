<?php
declare(strict_types=1);

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
     * @param array|null $parsedBody HTTP Post Body, if null $_POST will be used.
     * @param array|null $cookies HTTP Cookies, if null $_COOKIE will be used.
     * @param array|null $files HTTP Files, if null $_FILES will be used.
     * @return \Cake\Http\ServerRequest
     */
    public static function fromGlobals(
        ?array $server = null,
        ?array $query = null,
        ?array $parsedBody = null,
        ?array $cookies = null,
        ?array $files = null
    ): ServerRequest {
        $server = normalizeServer($server ?: $_SERVER);
        $uri = static::createUri($server);
        $sessionConfig = (array)Configure::read('Session') + [
                'defaults' => 'php',
                /** @phpstan-ignore-next-line */
                'cookiePath' => $uri->webroot,
            ];
        $session = Session::create($sessionConfig);
        $request = new ServerRequest([
            'environment' => $server,
            'uri' => $uri,
            'cookies' => $cookies ?: $_COOKIE,
            'query' => $query ?: $_GET,
            /** @phpstan-ignore-next-line */
            'webroot' => $uri->webroot,
            /** @phpstan-ignore-next-line */
            'base' => $uri->base,
            'session' => $session,
        ]);

        $session->setRequestCookies($request->getCookieParams());

        $request = static::marshalBodyAndRequestMethod($parsedBody ?? $_POST, $request);
        $request = static::marshalFiles($files ?? $_FILES, $request);

        return $request;
    }
}
