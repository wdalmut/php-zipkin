<?php
namespace Corley\Zipkin;

class Tracer
{
    use Tracer\SpanRegistry;

    private $logger;
    private $sampled;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->setIsSampled(true);
    }

    public function setIsSampled($sampled)
    {
        $this->sampled = (bool)$sampled;
    }

    public function isSampled()
    {
        return $this->sampled;
    }

    public function send()
    {
        if (!$this->isSampled()) {
            return;
        }

        return $this->logger->send($this->getSpans());
    }
}
