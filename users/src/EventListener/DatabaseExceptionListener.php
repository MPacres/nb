<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception\ConnectionException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DatabaseExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ConnectionException) {
            $response = new Response('Database connection error', Response::HTTP_SERVICE_UNAVAILABLE);
            $event->setResponse($response);
        }
    }
}
