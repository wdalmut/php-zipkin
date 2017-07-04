<?php
namespace Corley\Zipkin;

use Psr\Http\Message\MessageInterface;

trait SpanContext
{
    private static $TRACE_ID = null;

    private $id;
    private $parentId;

    private static function sharedTraceId()
    {
        if (!self::$TRACE_ID) {
            self::$TRACE_ID = bin2hex(openssl_random_pseudo_bytes(16));
        }

        return self::$TRACE_ID;
    }

    public static function getTraceId()
    {
        return self::sharedTraceId();
    }

    public function restoreContextFromRequest(MessageInterface $message)
    {
        $traceId = ($message->hasHeader("X-B3-TraceId")) ? $message->getHeader("X-B3-TraceId")[0] : null;
        $spanId = ($message->hasHeader('X-B3-SpanId')) ? $message->getHeader("X-B3-SpanId")[0] : null;
        $parentSpanId = ($message->hasHeader('X-B3-ParentSpanId')) ? $message->getHeader("X-B3-ParentSpanId")[0] : null;

        $this->restoreContext($traceId, $spanId, $parentSpanId);
    }

    public function restoreContext($traceId, $spanId, $parentSpanId = null)
    {
        if ($traceId) {
            self::$TRACE_ID = $traceId;
        }

        if ($spanId) {
            $this->setId($spanId);
        }

        if ($parentSpanId) {
            $this->setChildOf($parentSpanId);
        }
    }

    protected function setId($id = false)
    {
        $this->id = (!$id) ? bin2hex(openssl_random_pseudo_bytes(8)) : $id;

        return $this->id;
    }

    public function getContext()
    {
        return array_filter([
            'traceId' => self::sharedTraceId(),
            'id' => $this->getId(),
            'parentId' => $this->getParentId(),
        ], function($item) {
            return ($item) ? true : false;
        });
    }

    public function getId()
    {
        return ($this->id) ? $this->id : $this->setId();
    }

    public function setChildOf($spanId)
    {
        $this->parentId = ($spanId instanceOf SpanInterface) ? $spanId->getId() : $spanId;
    }

    public function getParentId()
    {
        return $this->parentId;
    }
}
