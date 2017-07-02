<?php
namespace Corley\Zipkin;

trait Annotation
{
    private $prototype;

    public function generateAnnotation($value, $serviceName, $dsn = false)
    {
        $annotation = [
            "timestamp" => intval(microtime(true) * 1000 * 1000),
            "value" => $value,
            "endpoint" => [
                "serviceName" => $serviceName,
            ],
        ];

        if ($dsn) {
            list($ipv4, $port) = $this->convertToIpv4Port($dsn);

            $annotation["endpoint"]["ipv4"] = $ipv4;
            $annotation["endpoint"]["port"] = $port;
        }

        return $annotation;
    }

    private function convertToIpv4Port($dsn)
    {
        list($ip, $port) = explode(":", $dsn);

        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            $ip = gethostbyname($ip);
        }

        return [$ip, $port];
    }
}
