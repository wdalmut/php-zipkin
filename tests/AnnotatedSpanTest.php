<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class AnnotatedSpanTest extends TestCase
{
    public function testCreateAnnotations()
    {
        $a = new AnnotatedSpan("oauth", "serviceName");
        $a->add("test");

        $data = json_decode(json_encode($a), true);

        $this->assertCount(1, $data["annotations"]);
        $this->assertEquals("test", $data["annotations"][0]["value"]);
    }
}
