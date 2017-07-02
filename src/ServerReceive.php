<?php
namespace Corley\Zipkin;

class ServerReceive extends AnnotatedSpan
{
    public function __construct($name, $serviceName, $dsn = null)
    {
        parent::__construct($name, $serviceName, $dsn);

        $this->add("sr");
    }

    public function sent()
    {
        $this->add("ss");
    }
}
