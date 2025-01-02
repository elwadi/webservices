<?php

namespace App\Message;

final class WpUpdateProduct
{
    
    public function __construct(
        public int $webSiteId,
        public int $productId,
    ) {
    }
}
