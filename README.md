# PHP ZipKin

[![Build Status](https://travis-ci.org/wdalmut/php-zipkin.svg?branch=master)](https://travis-ci.org/wdalmut/php-zipkin)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/wdalmut/php-zipkin/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/wdalmut/php-zipkin/?branch=master) 
[![Code Coverage](https://scrutinizer-ci.com/g/wdalmut/php-zipkin/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/wdalmut/php-zipkin/?branch=master)

A simple ZipKin implementation to trace your microservices based application


```php
// create the root span as server receive
$otherSpan = new ServerReceive("get_users", "oauth2", "auth.corley:80");
// add span to the tracer
$tracer->addSpan($otherSpan);

// restore the root span using headers (X-B3-TraceId, X-B3-SpanId)
$otherSpan->restoreContextFromHeaders($request);

// add a binary annotation on this span
$otherSpan->getBinaryAnnotations()->set("http.method", "GET");

// create a new span (service call)
$span = new ClientSend("getUser", "user.service", "user.corley:80");
// set span as child of the root span
$span->childOf($otherSpan);
// add span to the tracer
$tracer->addSpan($span);

// add binary annotation on this span
$span->getBinaryAnnotations()->set("error", true);

// sign a custom event on the current span
$span->add("reservedCustomer);

// close the client send span
$span->receive();

// close the server sent span
$otherSpan->sent("name", "hostname:port");
```

## Search for a Span

When you change your context typically you lost your span. You can use your
binary annotation to search for a particular span

```php
$rootSpan = $tracer->findOneBy("kind", "app.flow");

$span = new ClientSend("getUsers", "user.corley");
$span->setChildOf($rootSpan);
```

## Send data using the tracer

```php
$logger = new HttpLogger($zipkinHost);
$tracer = new Tracer($logger);

// ...

$tracer->send();
```

## Disable tracing

You can use the `NullLogger`

```php
$null = new NullLogger();
$tracer = new Tracer($null);
```

