<?php

namespace App\Message;

final class WpAiGenerator
{
   

    public function __construct(
        public int $webSiteId,
        public int $productId,
    ) {
    }
}
