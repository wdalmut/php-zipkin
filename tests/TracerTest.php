<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class TracerTest extends TestCase
{
    public function testSendOperation()
    {
        $logger = $this->prophesize("Corley\Zipkin\LoggerInterface");
        $logger->send(Argument::type("array"))->shouldBeCalledTimes(1);

        $tracer = new Tracer($logger->reveal());
        $tracer->send();
    }
}
