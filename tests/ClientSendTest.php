<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class ClientSendTest extends TestCase
{
    public function testCreateAnnotations()
    {
        $a = new ClientSend("oauth", "serviceName");
        $a->receive();

        $data = json_decode(json_encode($a), true);

        $this->assertCount(2, $data["annotations"]);
        $this->assertEquals("cs", $data["annotations"][0]["value"]);
        $this->assertEquals("cr", $data["annotations"][1]["value"]);
    }
}

