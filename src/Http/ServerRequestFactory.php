<?php
namespace CakeDC\Roadrunner\Http;

use Cake\Core\Configure;
use Cake\Error\Debugger;
use Cake\Http\ServerRequest;

class ServerRequestFactory extends \Cake\Http\ServerRequestFactory
{
    /**
     * override and inject a custom Session instance, until we get the ref. PR merged
     */
    public static function fromGlobals(
        array $server = null,
        array $query = null,
        array $body = null,
        array $cookies = null,
        array $files = null
    ) {
        $server = static::normalizeServer($server ?: $_SERVER);
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
