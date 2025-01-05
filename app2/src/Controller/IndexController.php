<?php

namespace App\Controller;

use App\Message\YourMessage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractController
{
    public function __construct(private MessageBusInterface $messageBus) {}

    #[Route('/index', name: 'app_index')]
    public function index(): Response
    {
        $message = new YourMessage('Hello, RabbitMQ!, !');
        $this->messageBus->dispatch($message);

        return new Response('Message envoyé!');
    }
}
