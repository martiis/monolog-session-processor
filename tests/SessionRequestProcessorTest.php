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

        $this->assertEquals(32, strlen($record));
        $this->assertEquals('a8bdb3d0f4f045948785f4eb153c4557', $record);
    }

    public function testSessionWithPrefix()
    {
        $sessionMock = $this->getMockBuilder(Session::class)->disableOriginalConstructor()->getMock();
        $sessionMock->expects($this->once())->method('getId')->willReturn('foo_id');

        $processor = new SessionRequestProcessor($sessionMock, 'prefix');
        $record = [];

        $record = $processor->processRecord($record);

        $this->assertArrayHasKey('extra', $record);
        $record = $record['extra'];

        $this->assertArrayHasKey('token', $record);
        $record = $record['token'];

        $this->assertEquals(32, strlen($record));
        $this->assertEquals('ad3a6c037c45cd5e09ba012a63e529fe', $record);
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

        $this->assertEquals(32, strlen($record));
        $this->assertEquals('17d594d77936eb46ce9b518ed750d7e1', $record);
    }
}
