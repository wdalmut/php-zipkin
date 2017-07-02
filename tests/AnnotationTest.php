<?php
namespace Corley\Zipkin;

use PHPUnit\Framework\TestCase;

class AnnotationTest extends TestCase
{
    public function testGenerateBaseAnnotations()
    {
        $mock = $this->getMockForTrait(Annotation::class);

        $annotation = $mock->generateAnnotation("custom", "serviceName");

        $this->assertArrayHasKey("timestamp", $annotation);

        $this->assertEquals("custom", $annotation["value"]);
        $this->assertEquals("serviceName", $annotation["endpoint"]["serviceName"]);

        $this->assertArrayNotHasKey("ipv4", $annotation["endpoint"]);
        $this->assertArrayNotHasKey("port", $annotation["endpoint"]);
    }

    public function testGenerateBaseAnnotationsWithIpv4()
    {
        $mock = $this->getMockForTrait(Annotation::class);

        $annotation = $mock->generateAnnotation("custom", "serviceName", "127.0.0.1:80");

        $this->assertArrayHasKey("timestamp", $annotation);

        $this->assertEquals("custom", $annotation["value"]);
        $this->assertEquals("serviceName", $annotation["endpoint"]["serviceName"]);

        $this->assertEquals("127.0.0.1", $annotation["endpoint"]["ipv4"]);
        $this->assertEquals("80", $annotation["endpoint"]["port"]);
    }

    /**
     * @group integration
     */
    public function testGenerateBaseAnnotationsWithHostname()
    {
        $mock = $this->getMockForTrait(Annotation::class);

        $annotation = $mock->generateAnnotation("custom", "serviceName", "www.google.it:80");

        $this->assertArrayHasKey("timestamp", $annotation);

        $this->assertEquals("custom", $annotation["value"]);
        $this->assertEquals("serviceName", $annotation["endpoint"]["serviceName"]);

        $this->assertNotNull($annotation["endpoint"]["ipv4"]);
        $this->assertEquals("80", $annotation["endpoint"]["port"]);
    }
}
