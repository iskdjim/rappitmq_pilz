<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/RabbitQmWrapper.php';

$rabbitQmWrapper = new RabbitQmWrapper();

$jsonString = file_get_contents(__DIR__.'/config.json');
$json = json_decode($jsonString, true);
$queue = $json['consume']['consumer']['listen'];

$channel = $rabbitQmWrapper->getChannel();

$callback = function ($msg) {
    echo ' Received Finale Message: ', $msg->body, "\n";
};

$rabbitQmWrapper->basicConsume($queue, '', false, true, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}

