<?php

declare(strict_types=1);

/**
 * @todo add note about creating a hardlink to the worker as an option:
 *
 * ```console
 * ln ../cakephp-roadrunner/worker/cakephp-worker.php cakephp-worker.php
 * ```
 */

ini_set('display_errors', 'stderr');

// You may need to change the `$rootDirectory` depending on where you've copied this file. This sample assumes the
// worker is in the same location as your `vendor` directory.
$rootDirectory = __DIR__;
include $rootDirectory . '/vendor/autoload.php';

use Cake\Http\Response;
use CakeDC\Roadrunner\Bridge;
use CakeDC\Roadrunner\ErrorHandler;
use Psr\Http\Message\ServerRequestInterface;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;

$bridge = new Bridge($rootDirectory);
$psr7 = new PSR7Worker(Worker::create(), new ServerRequestFactory(), new StreamFactory(), new UploadedFileFactory());

while (true) {
    try {
        $request = $psr7->waitRequest();
        if (!$request instanceof ServerRequestInterface) { // Termination request received
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(ErrorHandler::respond(400, $e));
        continue;
    }

    try {
        $response = $bridge->handle($request);
        $psr7->respond($response);
    } catch (\Throwable $e) {
        $psr7->respond(ErrorHandler::respond(500, $e));
    }
}
