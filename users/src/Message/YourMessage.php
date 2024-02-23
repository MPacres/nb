<?php

// src/Message/YourMessage.php

namespace App\Message;

class YourMessage
{
    private $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
