<?php

namespace App\Service;

interface MessageQueueServiceInterface
{
    public function sendMessage(string $queueName, string $messageBody): bool;
}
