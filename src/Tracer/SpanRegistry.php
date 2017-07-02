<?php
namespace Corley\Zipkin\Tracer;

use BadMethodCallException;
use Corley\Zipkin\SpanInterface;

trait SpanRegistry
{
    private $spans = [];

    public function addSpan(SpanInterface $span)
    {
        $this->spans[] = $span;
    }

    public function getSpans()
    {
        return $this->spans;
    }

    public function __call($method, $arguments)
    {
        $matches = [];
        if (preg_match("/find(One)?By$/", $method, $matches)) {
            $spans = $this->find($arguments[0], $arguments[1]);

            if (!empty($matches[1])) {
                return array_pop($spans);
            }

            return $spans;
        }

        throw new BadMethodCallException("Missing method: ${method}");
    }

    public function find($key, $value)
    {
        return array_filter($this->spans, function($span) use ($key, $value) {
            if ($span->getBinaryAnnotations()->get($key) === $value) {
                return true;
            }

            return false;
        });
    }
}
