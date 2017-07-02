<?php
namespace Corley\Zipkin;

class Tracer
{
    use Tracer\SpanRegistry;

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function send()
    {
        return $this->logger->send($this->getSpans());
    }
}
