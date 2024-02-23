<?php

namespace App\MessageHandler;

use App\Message\DisplayMessage;

class DisplayMessageHandler
{
    public function __invoke(DisplayMessage $message)
    {
        echo $message->getContent() . PHP_EOL;
    }
}
