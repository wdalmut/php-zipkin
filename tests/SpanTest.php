<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Zend\Diactoros\Request;
use Zend\Diactoros\Uri;

class SpanTest extends TestCase
{
    public function testBaseSpanFeatures()
    {
        $span = new Span("getUsers");
        $data = json_decode(json_encode($span), true);

        $this->assertEquals("getUsers", $data["name"]);
        $this->assertGreaterThan(0, $data["duration"]);
    }

    public function testRestoreFromContext()
    {
        $span = new Span("getUsers");
        $span->restoreContext("ciao", "mondo");

        $data = json_decode(json_encode($span), true);
        $this->assertEquals("ciao", $data["traceId"]);
        $this->assertEquals("mondo", $data["parentId"]);
        $this->assertArrayHasKey("id", $data);
    }

    public function testRestoreFromPsr7Request()
    {
        $request = (new Request())
            ->withUri(new Uri('http://example.com'))
            ->withMethod('GET')
            ->withHeader('X-B3-TraceId', "ciao")
            ->withHeader('X-B3-SpanId', 'mondo');

        $span = new Span("getUsers");
        $span->restoreContextFromRequest($request);

        $data = json_decode(json_encode($span), true);
        $this->assertEquals("ciao", $data["traceId"]);
        $this->assertEquals("mondo", $data["parentId"]);
        $this->assertArrayHasKey("id", $data);
    }

    public function testRestoreFromAnEmptyPsr7Request()
    {
        $request = (new Request())
            ->withUri(new Uri('http://example.com'))
            ->withMethod('GET');

        $span = new Span("getUsers");
        $span->restoreContextFromRequest($request);

        $data = json_decode(json_encode($span), true);
        $this->assertArrayHasKey("traceId", $data);
        $this->assertArrayNotHasKey("parentId", $data);
        $this->assertArrayHasKey("id", $data);
    }

    /**
     * @dataProvider getSpansAndIds
     */
    public function testSetChildOfAnotherSpan($rootSpan)
    {
        $span = new Span("getUsers");
        $span->setChildOf($rootSpan);

        $data = json_decode(json_encode($span), true);
        $this->assertArrayHasKey("parentId", $data);
    }

    public function getSpansAndIds()
    {
        return [
            [new Span("Root")],
            [bin2hex(openssl_random_pseudo_bytes(8))],
        ];
    }

    public function testAddBinaryAnnotations()
    {
        $span = new Span("getUsers");
        $span->getBinaryAnnotations()->set("http.method", "GET");
        $span->getBinaryAnnotations()->set("http.url", "http://test.corley.it/test");

        $data = json_decode(json_encode($span), true);

        $this->assertCount(2, $data["binaryAnnotations"]);

        $this->assertEquals("http.method", $data["binaryAnnotations"][0]["key"]);
        $this->assertEquals("GET", $data["binaryAnnotations"][0]["value"]);

        $this->assertEquals("http.url", $data["binaryAnnotations"][1]["key"]);
        $this->assertEquals("http://test.corley.it/test", $data["binaryAnnotations"][1]["value"]);
    }
}
