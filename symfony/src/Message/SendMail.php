<?php

namespace App\Message;

final class SendMail
{
    public function __construct(
        private string $content,private string $name
    ) {
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
