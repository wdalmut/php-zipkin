<?php
namespace Corley\Zipkin\Logger;

use Corley\Zipkin\LoggerInterface;

class HttpLogger implements LoggerInterface
{
	public function __construct($options = [])
    {
        $defaults = [
            'host' => 'http://127.0.0.1:9144',
            'endpoint' => '/api/v1/spans',
            'contextOptions' => []
        ];

        $this->options = array_replace($defaults, $options);
    }

    public function send(array $spans)
    {
        $contextOptions = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($spans),
                'ignore_errors' => true
            ]
        ];
        $context = stream_context_create(array_replace_recursive($contextOptions, $this->options['contextOptions']));
        @file_get_contents($this->options['host'] . $this->options['endpoint'], false, $context);
    }
}
