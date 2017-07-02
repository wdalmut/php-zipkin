<?php
namespace Corley\Zipkin;

use JsonSerializable;

class BinaryAnnotations implements JsonSerializable
{
    private $data;

    public function __construct()
    {
        $this->data = [];
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        return ($this->has($key)) ? $this->data[$key] : null;
    }

    public function has($key)
    {
        return (array_key_exists($key, $this->data)) ? true : false;
    }

    public function toArray()
    {
        $data = [];

        array_walk($this->data, function($value, $key) use (&$data) {
            $data[] = ["key" => (string)$key, "value" => (string)$value];
        });

        return $data;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
