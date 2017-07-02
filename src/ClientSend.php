<?php
namespace Corley\Zipkin;

class ClientSend extends AnnotatedSpan
{
    public function __construct($name, $serviceName, $dsn = null)
    {
        parent::__construct($name, $serviceName, $dsn);

        $this->add("cs");
    }

    public function receive()
    {
        $this->add("cr");
    }
}

