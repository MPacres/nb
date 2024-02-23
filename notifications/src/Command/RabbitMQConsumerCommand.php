<?php

// src/Command/RabbitMQConsumerCommand.php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Service\RabbitMQService;

class RabbitMQConsumerCommand extends Command
{
    protected static $defaultName = 'app:rabbitmq:consume';

    private $rabbitMQService;

    public function __construct(RabbitMQService $rabbitMQService)
    {
        $this->rabbitMQService = $rabbitMQService;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setHelp('This command starts the RabbitMQ consumer worker to process messages from the queue.')
            ->setName('app:rabbitmq:consume')
            ->setDescription('Description of the command');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('RabbitMQ consumer worker started.');

        // Start consuming messages
        $this->rabbitMQService->consume(function ($message) use ($output) {
            // Process message
            $output->writeln('Received message: ' . $message->getBody());
        });

        return Command::SUCCESS;
    }
}
