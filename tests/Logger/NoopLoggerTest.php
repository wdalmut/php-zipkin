<?php
namespace Corley\Zipkin\Logger;

use PHPUnit\Framework\TestCase;

class NoopLoggerTest extends TestCase
{
    public function testNoopLogger()
    {
        $noop = new NoopLogger();

        $this->assertNull($noop->send([]));
    }
}

