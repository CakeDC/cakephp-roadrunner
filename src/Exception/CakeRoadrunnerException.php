<?php

namespace CakeDC\Roadrunner\Exception;

use Cake\Http\Exception\HttpException;
use Throwable;

class CakeRoadrunnerException extends HttpException
{
    public function __construct($message = '', ?int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}