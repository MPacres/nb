<?php
// src/Service/RabbitMQService.php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService implements MessageQueueServiceInterface
{
    private $connection;

    public function __construct(string $host, int $port, string $user, string $password)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
    }

    public function sendMessage(string $queueName, string $messageBody): bool
    {

            $channel = $this->connection->channel();
            $channel->queue_declare($queueName, false, false, false, false);
            $message = new AMQPMessage($messageBody);
       
            try {
                $channel->basic_publish($message, '', $queueName);
                $channel->close();
                return true; // Return true on successful message sending
            } catch (\Exception $e) {
                // Handle any exceptions or errors here
                // Log the error, etc.
                $channel->close();
                return false; // Return false if message sending fails
            }
        
    }
}
