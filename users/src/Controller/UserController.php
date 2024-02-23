<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

use Doctrine\ORM\EntityManagerInterface;

use App\Service\RabbitMQService;
use App\Repository\UsersRepository;
use App\Entity\Users;


class UserController extends AbstractController
{
    public function __construct(

        RabbitMQService $rabbitMQService,
        EntityManagerInterface $entityManager,
        UsersRepository $usersRepository
    ) {
 
        $this->rabbitMQService = $rabbitMQService;
        $this->entityManager = $entityManager;
        $this->usersRepository = $usersRepository;
    }
    #[Route('/users', name: 'send_message', methods: ['POST'])]
    public function index(Request $request): response
    {

        $data = json_decode($request->getContent(), true);

        if(null === $data && JSON_ERROR_NONE !== json_last_error()) {
            return new Response('Invalid JSON data', Response::HTTP_BAD_REQUEST);
        }

        $requiredFields = ['email', 'firstName', 'lastName'];

        $errorFields = array_filter($requiredFields, function ($field) use ($data) {
            return !isset($data[$field]) || empty($data[$field]);
        });

        if (!empty($errorFields)) {
            $errorMessage = 'Required fields are missing or empty: ' . implode(', ', $errorFields);
            return new Response($errorMessage, Response::HTTP_BAD_REQUEST);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return new Response('Invalid email format.', Response::HTTP_BAD_REQUEST);
        }
        try {
            $user = new Users();
            $user->setEmail($data['email']);
            $user->setFirstName($data['firstName']);
            $user->setLastName($data['lastName']);
            $this->usersRepository->save($user);
        } catch (\Throwable $th) {
            return new Response('Database error Table/view does not exist', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
       

        try {
        $send = $this->rabbitMQService->sendMessage('notification', json_encode($data));
        return new Response('Message Sent!', Response::HTTP_OK);
          
        } catch (\Exception $e) {
            dd($e);
            return new Response('Cant connect to message broker!', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
