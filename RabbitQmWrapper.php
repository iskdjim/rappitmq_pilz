<?php

declare(strict_types=1);

include(__DIR__ . '/config.php');
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__.'/vendor/autoload.php';


class RabbitQmWrapper
{
    /**
     * @var AMQPStreamConnection
     */
    private $connection;

    /**
     * @var AMQPChannel
     */
    private $channel;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(HOST, PORT, USER, PASS, VHOST);
        $this->channel = $this->connection->channel();
    }

    public function declareExchange(
        string $exchangeName,
        string $type,
        bool $passive,
        bool $durable,
        bool $auto_delete = true,
        bool $internal = false,
        bool $nowait = false,
        array $arguments = array(),
        $ticket = null
    ) {
        $this->channel->exchange_declare($exchangeName, $type, $passive, $durable, $auto_delete, $internal, $nowait, $arguments, $ticket);
    }

    public function declareQueue(
        string $queueName,
        bool $passive,
        bool $durable,
        bool $exclusive,
        bool $autoDelete = true,
        bool $nowait = false,
        array $arguments = array(),
        $ticket = null
    ) {
        $this->channel->queue_declare($queueName, $passive, $durable, $exclusive, $autoDelete, $nowait, $arguments, $ticket);
    }

    public function bindQueue(string $queue, string $exchange)
    {
        $this->channel->queue_bind($queue, $exchange);
    }

    public function basicPublish($msg, $exchange)
    {
        $this->channel->basic_publish($msg, $exchange);
    }

    public function channelClose()
    {
        $this->channel->close();
    }

    public function connectionClose()
    {
        $this->connection->close();
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function basicConsume(
        string $queueName,
        string $consumerTag = '',
        bool $noLocal = false,
        bool $noAck = false,
        bool $exclusive = false,
        bool $nowait = false,
        $callback = null,
        $ticket = null,
        array $arguments = array())
    {
        $this->channel->basic_consume($queueName, $consumerTag, $noLocal, $noAck, $exclusive, $nowait, $callback, $ticket, $arguments);
    }
}
