<?php
namespace Corley\Zipkin\Logger;

use Corley\Zipkin\LoggerInterface;

class NullLogger implements LoggerInterface
{
    public function send(array $spans)
    {
        return null;
    }
}

