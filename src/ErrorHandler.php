<?php
declare(strict_types=1);

namespace CakeDC\Roadrunner;

use Cake\Http\Response;

class ErrorHandler
{
    public const TITLE = 'CakeDC Roadrunner Error';
    
    /**
     * Return an error response.
     *
     * @param int $status HTTP status code
     * @param \Throwable $e Instance of Throwable
     * @param bool $debug Whether to add stack trace, default is false.
     * @return \Cake\Http\Response
     */
    public static function response(int $status, \Throwable $e, bool $debug = false): Response
    {
        return new Response([
            'status' => $status,
            'body' => json_encode([
                'title' => self::TITLE,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'trace' => $debug ? $e->getTrace() : null,
            ]),
        ]);
    }
}
