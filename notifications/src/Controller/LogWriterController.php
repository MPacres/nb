<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Service\RabbitMQService;
use PhpAmqpLib\Message\AMQPMessage;

class LogWriterController extends AbstractController
{


    #[Route('/log/writer', name: 'app_log_writer')]
    public function consumeMessages(RabbitMQService $rabbitMQService): Response
    {
        $rabbitMQService->consume(function (AMQPMessage $message) {
            // Handle the received message
            echo 'Received message: ' . $message->getBody() . PHP_EOL;

            
        });


     
        // This code will not be reached until the consume method is stopped (e.g., by manually terminating the process)
        return new Response('', Response::HTTP_OK);
    }
}
