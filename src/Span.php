<?php
namespace Corley\Zipkin;

use JsonSerializable;

class Span implements SpanInterface, JsonSerializable
{
    use SpanContext;

    protected $value;

    public function __construct($name)
    {
        $this->value = [
            "name" => $name,
            "timestamp" => intval(microtime(true) * 1000 * 1000),
            "annotations" => [],
            "binaryAnnotations" => new BinaryAnnotations(),
        ];
    }

    public function setName($name)
    {
        $this->value["name"] = $name;
    }

    protected function close($data)
    {
        return array_replace($data, $this->getContext());
    }

    public function toArray()
    {
        return $this->close($this->value);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function getBinaryAnnotations()
    {
        return $this->value["binaryAnnotations"];
    }
}
