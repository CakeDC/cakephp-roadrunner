<?php

namespace CakeDC\Roadrunner\Test\TestCase;

use Cake\TestSuite\TestCase;
use CakeDC\Roadrunner\Bridge;
use CakeDC\Roadrunner\Exception\CakeRoadrunnerException;
use CakeDC\Roadrunner\Http\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;

class BridgeTest extends TestCase
{
    public function test_handle(): void
    {
        $this->markTestIncomplete();
        $rootDir = __DIR__ . '/../test_app';
        $response = (new Bridge($rootDir))->handle(ServerRequestFactory::fromGlobals());
        $this->assertInstanceOf(ServerRequestInterface::class, $response);
    }

    public function test_construct_throws_exception_when_root_dir_not_found(): void
    {
        $rootDir = '/dev/null/cakephp-roadrunner-'. md5((string)microtime(true));

        $this->expectException(CakeRoadrunnerException::class);
        $this->expectExceptionMessage(sprintf(CakeRoadrunnerException::ROOT_DIR_NOT_FOUND, $rootDir));
        (new Bridge($rootDir));
    }

    public function test_construct_throws_exception_when_app_not_created(): void
    {
        $this->markTestIncomplete();
        $this->expectException(CakeRoadrunnerException::class);
        $this->expectExceptionMessage(sprintf(CakeRoadrunnerException::APP_INSTANCE_NOT_CREATED));
        (new Bridge(__DIR__));
    }
}