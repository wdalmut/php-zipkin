<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class ServerReceiveTest extends TestCase
{
    public function testCreateAnnotations()
    {
        $a = new ServerReceive("oauth", "serviceName");
        $a->sent();

        $data = json_decode(json_encode($a), true);

        $this->assertCount(2, $data["annotations"]);
        $this->assertEquals("sr", $data["annotations"][0]["value"]);
        $this->assertEquals("ss", $data["annotations"][1]["value"]);
    }
}

