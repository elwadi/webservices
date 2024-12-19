<?php

namespace App\MessageHandler;

use App\Entity\Company;
use App\Entity\User;
use App\Message\SendMail;
use Doctrine\Persistence\ManagerRegistry;
use Monolog\Logger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendMailHandler
{
    public function __construct(private ManagerRegistry $managerRegistry) {}

    public function __invoke(SendMail $message): void
    {
        $user = $this->managerRegistry->getRepository(User::class)->find($message->getUserId());

        if($user instanceof User){
            $user->setCounter($user->getCounter()-1);

            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            echo $user->getId()." => ".$user->getCounter();
        }
    }
}
