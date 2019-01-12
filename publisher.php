<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/RabbitQmWrapper.php';

$rabbitQmWrapper = new RabbitQmWrapper();

$jsonString = file_get_contents(__DIR__.'/config.json');
$json = json_decode($jsonString, true);
$exchange = $json['publish']['publisher']['exchange'];

$counter = 0;

while($counter < sizeof($json['messages'])) {
    $msg = new AMQPMessage($json['messages'][$counter]);
    $rabbitQmWrapper->basicPublish($msg, $exchange);
    sleep(3);
    $counter++;
}