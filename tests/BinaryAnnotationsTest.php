<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class BinaryAnnotationsTest extends TestCase
{
    public function testSetGet()
    {
        $t = new BinaryAnnotations();
        $t->set("ciao", "mondo");

        $this->assertSame(false, $t->has("mondo"));
        $this->assertSame(true, $t->has("ciao"));

        $this->assertSame(null, $t->get("mondo"));
        $this->assertEquals("mondo", $t->get("ciao"));
    }

    public function testCanBeSerialized()
    {
        $t = new BinaryAnnotations();
        $t->set("ciao", "mondo");
        $t->set("hello", "world");

        $data = json_decode(json_encode($t), true);

        $this->assertEquals([
            ["key" => "ciao", "value" => "mondo"],
            ["key" => "hello", "value" => "world"],
        ], $data);
    }
}
