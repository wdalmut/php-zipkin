<?php
namespace Corley\Zipkin\Logger;

use PHPUnit\Framework\TestCase;

class NullLoggerTest extends TestCase
{
    public function testLogSend()
    {
        $logger = new NullLogger();
        $data = $logger->send([]);

        $this->assertNull($data);
    }
}
