<?php

namespace App\MessageHandler;

use App\Entity\Product;
use App\Entity\WpSite;
use App\Message\WpImportProduct;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class WpImportProductHandler
{
    public function __construct(private ManagerRegistry $managerRegistry) {

    }
    public function __invoke(WpImportProduct $message): void
    {
        $wp=$this->managerRegistry->getRepository(WpSite::class)->find($message->webId);
        if($wp instanceof WpSite){
            $product=$this->managerRegistry->getRepository(Product::class)->find($message->productId);
            if(!$product instanceof Product){
                $product=new Product();
                $product->setWebsite($wp);
                $product->setProductId($message->productId);
            }
            $product->setProductName($message->name);
            $product->setProductDescription($message->description);
            $this->managerRegistry->getManager()->persist($product);
            $this->managerRegistry->getManager()->flush();
        }
    }
}
