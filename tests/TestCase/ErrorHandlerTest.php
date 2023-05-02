<?php

namespace CakeDC\Roadrunner\Test\TestCase;

use Cake\TestSuite\TestCase;
use CakeDC\Roadrunner\ErrorHandler;

class ErrorHandlerTest extends TestCase
{
    public function test_response(): void
    {
        $throwable = new \Exception($message = 'test');
        $response = ErrorHandler::response(400, $throwable, true);
        $this->assertEquals(400, $response->getStatusCode());
        $jsonObj = json_decode((string)$response->getBody());
        $this->assertEquals(ErrorHandler::TITLE, $jsonObj->title);
        $this->assertEquals(get_class($throwable), $jsonObj->exception);
        $this->assertEquals($message, $jsonObj->message);
        $this->assertNotEmpty($jsonObj->trace);
    }
}