<?php
namespace Corley\Zipkin;

interface LoggerInterface
{
    public function send(array $data);
}
