<?php

namespace CakeDC\Roadrunner;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;

class ErrorHandler
{
    /**
     * Return an error response.
     *
     * @param int $status HTTP status code
     * @param \Throwable $e Instance of Throwable
     * @param bool $debug Whether to add stack trace, default is false.
     * @return ResponseInterface
     */
    public static function respond(int $status, \Throwable $e, bool $debug = false): ResponseInterface
    {
        return new Response([
            'status' => $status,
            'body' => json_encode([
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $debug ? $e->getTrace() : null
            ]),
        ]);
    }
}