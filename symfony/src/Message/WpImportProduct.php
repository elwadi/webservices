<?php

namespace App\Message;

final class WpImportProduct
{
   

    public function __construct(
        public int $webId,
        public int $productId,
        public string $name,
        public string $description
    ) {

    }
}
