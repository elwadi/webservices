<?php

namespace App\Controller;

use App\Message\SendMail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class MessageController extends AbstractController
{
    #[Route('/message', name: 'app_message')]
    public function index(MessageBusInterface $bus): Response
    {
        for ($i = 10; $i < 1000; ++$i) {
            $bus->dispatch(new SendMail('Hello World! # '.$i));
        }

        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }
}
