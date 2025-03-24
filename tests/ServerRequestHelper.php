<?php

namespace CakeDC\Roadrunner\Test;

use Laminas\Diactoros\ServerRequest;

class ServerRequestHelper
{
    /**
     * Returns a big list of $_SERVER parameterss.
     *
     * @param array $serverParams A list of $_SERVER parameters to be merged in with the default.
     * @return array
     */
    public static function defaultServerParams(array $serverParams = []): array
    {
        return array_merge([
            'REQUEST_URI' => 'http://localhost:8080/.json',
            'REMOTE_ADDR' => '127.0.0.1',
            'REQUEST_METHOD' => 'GET',
            'RR_RELAY' => 'pipes',
            'RR_RPC' => 'tcp://127.0.0.1:6001',
            'RR_MODE' => 'http',
            'PHP_SELF' => 'cakephp-worker.php',
            'SCRIPT_NAME' => 'cakephp-worker.php',
            'SCRIPT_FILENAME' => 'cakephp-worker.php',
            'PATH_TRANSLATED' => 'cakephp-worker.php',
            'DOCUMENT_ROOT' => '',
            'REQUEST_TIME_FLOAT' => 1642567499.4602,
            'REQUEST_TIME' => 1642567499,
            'argv' => ['cakephp-worker.php'],
            'argc' => '1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:96.0) Gecko/20100101 Firefox/96.0',
            'HTTP_CONNECTION' => 'keep-alive',
            'HTTP_COOKIE' => 'Phpstorm-37de0ca2=ca4892d1-6b8b-4220-81a6-ad8b11dde23d; CookieAuth=%5B%22cnizzardini%22%2C%22%242y%2410%24UnrYDgq2SfjrQH.XNWBlc.JuneHeyRN%5C%2F6858e3DCMAa%5C%2FxWRYoxkf6%22%5D; PHPSESSID=llvucp06rquph426a6mi6qbnhn; csrfToken=7DhdIaeWFX60MWTVX5QWomYwZGNlMGE1ODk1OGQ1MTc1ZTQ5ODYxODNiMDM3YjUwZDA3MjExZjA%3D; Phpstorm-37de0ca4=34bada71-56a9-477c-9b26-e3268c411ad2',
            'HTTP_SEC_FETCH_MODE' => 'navigate',
            'HTTP_ACCEPT_LANGUAGE' => 'en-US,en;q=0.5',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            'HTTP_UPGRADE_INSECURE_REQUESTS' => '1',
            'HTTP_SEC_FETCH_SITE' => 'none',
            'HTTP_SEC_GPC' => '1',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
            'HTTP_SEC_FETCH_USER' => '?1',
            'HTTP_SEC_FETCH_DEST' => 'document',
            'HTTP_CACHE_CONTROL' => 'max-age=0',
        ], $serverParams);
    }
}