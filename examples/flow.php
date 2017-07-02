<?php

use Corley\Zipkin\Tracer;
use Corley\Zipkin\Logger\HttpLogger;

use Corley\Zipkin\ClientSend;
use Corley\Zipkin\ServerReceive;

require __DIR__ . '/../vendor/autoload.php';

$logger = new HttpLogger(["host" => "http://localhost:9411"]);
$tracer = new Tracer($logger);

$rootSpan = new ServerReceive("list_my_users", "App 1", "10.64.98.14:80");
if (!empty($_SERVER['HTTP_X_B3_SPANID'])) {
    $rootSpan->restoreContext($_SERVER['HTTP_X_B3_TRACEID'], $_SERVER['HTTP_X_B3_SPANID']);
}
$tracer->addSpan($rootSpan);

$rootSpan->getBinaryAnnotations()->set("kind", "root");

sleep(1);

/*****************************************/

$span = new ClientSend("get_users", "App 1");
$tracer->addSpan($span);

$parent = $tracer->findOneBy("kind", "root");
$span->setChildOf($parent);

$parent->add("ClientCall");

sleep(1);

$span->getBinaryAnnotations()->set("http.method", "GET");
$span->getBinaryAnnotations()->set("http.url", "http://10.64.98.12:80");

$span->receive();

/****************************************/

/*****************************************/

$span = new ClientSend("Query", "App 1");
$tracer->addSpan($span);

$parent = $tracer->findOneBy("kind", "root");
$span->setChildOf($parent);

$span->add("query");

usleep(150);

$span->getBinaryAnnotations()->set("db.sql", "SELECT * FROM users");
$span->getBinaryAnnotations()->set("db.params", json_encode(["username" => "walter.dalmut@gmail.com"]));

$span->receive();

/****************************************/

sleep(1);

$rootSpan->sent();

$tracer->send();
