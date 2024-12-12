<?php

namespace App\MessageHandler;

use App\Message\SendMail;
use Monolog\Logger;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SendMailHandler
{
    public function __invoke(SendMail $message): void
    {
        $logger = new Logger('mailer');
        $logger->info($message->getContent());
        echo $message->getContent();
    }
}
