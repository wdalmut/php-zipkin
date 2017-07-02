<?php
namespace Corley\Zipkin;

class AnnotatedSpan extends Span
{
    use Annotation;

    private $serviceName;
    private $dsn;

    public function __construct($name, $serviceName, $dsn = null)
    {
        parent::__construct($name);

        $this->serviceName = $serviceName;
        $this->dsn = $dsn;
    }

    public function add($name)
    {
        $this->value["annotations"][] = $this->generateAnnotation($name, $this->serviceName, $this->dsn);
    }
}

