<?php
// src/Controller/ExceptionController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends AbstractController
{
    public function notFound(): Response
    {
        return new Response('404', Response::HTTP_NOT_FOUND);
    }
}
