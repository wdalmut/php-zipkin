<?php
namespace Corley\Zipkin\Tracer;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

use Corley\Zipkin\ServerReceive;
use Corley\Zipkin\ClientSend;

class SpanRegistryTest extends TestCase
{
    public function testFindByAnnotations()
    {
        $mock = $this->getMockForTrait(SpanRegistry::class);

        $s = new ServerReceive("test", "ok");
        $mock->addSpan($s);
        $s->getBinaryAnnotations()->set("kind", "app.flow");

        $r = new ClientSend("getUsers", "test");
        $mock->addSpan($r);
        $r->getBinaryAnnotations()->set("kind", "app.flow");
        $r->receive();

        $s->sent();

        $spans = $mock->findBy("kind", "app.flow");

        $this->assertCount(2, $spans);
        $this->assertSame($s, $spans[0]);
    }

    public function testFindOneByAnnotations()
    {
        $mock = $this->getMockForTrait(SpanRegistry::class);

        $s = new ServerReceive("test", "ok");
        $mock->addSpan($s);
        $s->getBinaryAnnotations()->set("kind", "app.flow");

        $r = new ClientSend("getUsers", "test");
        $mock->addSpan($r);
        $r->receive();

        $s->sent();

        $span = $mock->findOneBy("kind", "app.flow");

        $this->assertSame($s, $span);
    }

    /**
     * @expectedException BadMethodCallException
     */
    public function testCallMissingMethod()
    {
        $mock = $this->getMockForTrait(SpanRegistry::class);
        $mock->cercaPerNome("ciao");
    }
}
