<?php

namespace App\Message;

final class SendMail
{
    public function __construct(private int $userId) {
    }


    public function getUserId(): int
    {
        return $this->userId;
    }
}
