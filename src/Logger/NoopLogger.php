<?php
namespace Corley\Zipkin\Logger;

use Corley\Zipkin\LoggerInterface;

class NoopLogger implements LoggerInterface
{
    public function send(array $spans)
    {

    }
}
