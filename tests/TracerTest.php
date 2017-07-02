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

    /**
     * @dataProvider getNotSampled
     */
    public function testIsSampled($sampled, $count)
    {
        $logger = $this->prophesize("Corley\Zipkin\LoggerInterface");
        $logger->send(Argument::type("array"))->shouldBeCalledTimes($count);

        $tracer = new Tracer($logger->reveal());
        $tracer->setIsSampled($sampled);
        $tracer->send();
    }

    public function getNotSampled()
    {
        return [
            [false, 0],
            [0, 0],
            ["0", 0],
            [true, 1],
            [1, 1],
            [true, 1]
        ];
    }
}
