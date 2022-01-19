<?php

namespace CakeDC\Roadrunner;

use CakeDC\Roadrunner\Http\ServerRequestFactory;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;

class Psr7WorkerFactory
{
    /**
     * Creates PSR7Worker.
     *
     * @return \Spiral\RoadRunner\Http\PSR7Worker
     */
    public static function create(): PSR7Worker
    {
        return new PSR7Worker(
            Worker::create(), new ServerRequestFactory(), new StreamFactory(), new UploadedFileFactory()
        );
    }
}