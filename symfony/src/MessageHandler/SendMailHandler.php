<?php

namespace App\MessageHandler;

use App\Entity\Company;
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
        $company= new Company();

        $company->setName($message->getName());
        $company->setDescription($message->getContent());

        $entityManager = $this->managerRegistry->getManager();
        $entityManager->persist($company);
        $entityManager->flush();
        
        echo $company->getId()." => ".$message->getName();
    }
}
