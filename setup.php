<?php

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/RabbitQmWrapper.php';

$rabbitQmWrapper = new RabbitQmWrapper();

$exchangeDecrypt = 'decrypt_exchange';
$exchangeAuth = 'auth_exchange';
$queueDecrypt = 'decrypt_queue';
$queueAuth = 'auth_queue';

$jsonString = file_get_contents(__DIR__.'/config.json');
$json = json_decode($jsonString, true);
$exchange = $json['publish']['publisher']['exchange'];

$counter = 0;

foreach($json['pipes'] as $pipe ) {
    $exchange = $pipe['exchange'];
    $queue = $pipe['queue'];
    $rabbitQmWrapper->declareExchange($exchange, 'fanout', false, true, false);
    $rabbitQmWrapper->declareQueue($queue, false, false, false, false);
    $rabbitQmWrapper->bindQueue($queue, $exchange);
}

$rabbitQmWrapper->channelClose();
$rabbitQmWrapper->connectionClose();