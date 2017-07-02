<?php
namespace Corley\Zipkin;

use JsonSerializable;
use Psr\Http\Message\MessageInterface;

class Span implements SpanInterface, JsonSerializable
{
    private static $TRACE_ID = null;

    protected $value;

    public function __construct($name)
    {
        $this->value = [
            "id" => bin2hex(openssl_random_pseudo_bytes(8)),
            "name" => $name,
            "timestamp" => intval(microtime(true) * 1000 * 1000),
            "annotations" => [],
            "binaryAnnotations" => new BinaryAnnotations(),
        ];

        if (!self::$TRACE_ID) {
            self::$TRACE_ID = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

    public function restoreContextFromRequest(MessageInterface $message)
    {
        $traceId = ($message->hasHeader("X-B3-TraceId")) ? $message->getHeader("X-B3-TraceId")[0] : null;
        $spanId = ($message->hasHeader('X-B3-SpanId')) ? $message->getHeader("X-B3-SpanId")[0] : null;

        $this->restoreContext($traceId, $spanId);
    }

    public function restoreContext($traceId, $spanId)
    {
        if ($traceId) {
            self::$TRACE_ID = $traceId;
        }

        if ($spanId) {
            $this->setChildOf($spanId);
        }
    }

    public function getId()
    {
        return $this->value["id"];
    }

    public static function getTraceId()
    {
        return self::$TRACE_ID;
    }

    public function setName($name)
    {
        $this->value["name"] = $name;
    }

    public function setChildOf($spanId)
    {
        $this->value["parentId"] = ($spanId instanceOf SpanInterface) ? $spanId->getId() : $spanId;
    }

    protected function close($data)
    {
        $data["traceId"] = self::$TRACE_ID;

        return $data;
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
