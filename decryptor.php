<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/RabbitQmWrapper.php';

$rabbitQmWrapper = new RabbitQmWrapper();

$channel = $rabbitQmWrapper->getChannel();

$callback = function ($req) {
    $newMessage = $req->body;
    $jsonString = file_get_contents(__DIR__.'/config.json');
    $json = json_decode($jsonString, true);
    $exchange = $json['publish']['decryptor']['exchange'];

    $req->delivery_info['channel']->basic_publish(
        new AMQPMessage($newMessage),
        $exchange
    );
};

$jsonString = file_get_contents(__DIR__.'/config.json');
$json = json_decode($jsonString, true);
$queue = $json['consume']['decryptor']['listen'];
$ack = $json['consume']['decryptor']['ack'];

$rabbitQmWrapper->basicConsume($queue, '', false, $ack, false, false, $callback);

while (count($channel->callbacks)) {
    $channel->wait();
}