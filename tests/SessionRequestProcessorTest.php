<?php

namespace Martiis\Monolog\Processor;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionRequestProcessorTest extends \PHPUnit_Framework_TestCase
{
    public function testSessionFound()
    {
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->expects($this->once())->method('getId')->willReturn('foo_id');

        $processor = new SessionRequestProcessor($sessionMock);
        $record = [];

        $record = $processor->processRecord($record);

        $this->assertArrayHasKey('extra', $record);
        $record = $record['extra'];

        $this->assertArrayHasKey('token', $record);
        $record = $record['token'];

        $this->assertStringStartsWith('foo_id', $record);
    }

    public function testSessionWithException()
    {
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->expects($this->once())->method('getId')->willThrowException(new \RuntimeException('broken!'));

        $processor = new SessionRequestProcessor($sessionMock);
        $record = [];

        $record = $processor->processRecord($record);

        $this->assertArrayHasKey('extra', $record);
        $record = $record['extra'];

        $this->assertArrayHasKey('token', $record);
        $record = $record['token'];

        $this->assertStringStartsWith('????????', $record);
    }
}
