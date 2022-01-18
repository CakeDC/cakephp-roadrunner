<?php

namespace CakeDC\Roadrunner\Exception;

use Cake\Http\Exception\HttpException;
use Throwable;

class CakeRoadrunnerException extends HttpException
{
    public const ROOT_DIR_NOT_FOUND = 'Root directory %s not found.';
    public const APP_INSTANCE_NOT_CREATED = 'Unable to create instance of Application.';

    public function __construct($message = '', ?int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}