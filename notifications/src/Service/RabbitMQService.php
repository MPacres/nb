<?php

namespace App\Service;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;
    private $channel;
    private $queueName;

    public function __construct(string $host, int $port, string $user, string $password, string $queueName)
    {
        $this->connection = new AMQPStreamConnection($host, $port, $user, $password);
        $this->channel = $this->connection->channel();
        $this->queueName = $queueName;
        
    }

    public function consume(callable $callback): void
    {
        try {

            $logDirectory = '../public/logs';

            while (true) {
                $message = $this->channel->basic_get($this->queueName);
                if ($message instanceof AMQPMessage) {
                    // Generate filename using Unix timestamp
                    $filename = "file_from_users".time() . '.log';           
                    // Write the message content to the log file
                    file_put_contents($filename, $message->getBody() . PHP_EOL, FILE_APPEND);        
                    
                    // dd($filename);
                    // Acknowledge the message
                    $this->channel->basic_ack($message->delivery_info['delivery_tag']);
                }
            }

        } catch (\Exception $e) {
            // Log or handle the exception
            echo "Exception: " . $e->getMessage() . "\n";
        }
    }

    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }
}
