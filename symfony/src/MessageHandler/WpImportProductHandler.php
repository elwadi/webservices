<?php

namespace App\MessageHandler;

use App\Entity\Product;
use App\Entity\WpSite;
use App\Message\WpAiGenerator;
use App\Message\WpImportProduct;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class WpImportProductHandler
{
    public function __construct(private ManagerRegistry $managerRegistry,private MessageBusInterface $bus) {

    }
    public function __invoke(WpImportProduct $message): void
    {
        $wp=$this->managerRegistry->getRepository(WpSite::class)->find($message->webId);
        if($wp instanceof WpSite){
            $product=$this->managerRegistry->getRepository(Product::class)->findOneBy(['productId' => $message->productId]);
            if(!$product instanceof Product){
                $product=new Product();
                $product->setWebsite($wp);
                $product->setProductId($message->productId);
            }
            $product->setProductName($message->name);
            $product->setProductDescription($message->description);
            $this->managerRegistry->getManager()->persist($product);
            $this->managerRegistry->getManager()->flush();
            
            $this->bus->dispatch(new WpAiGenerator($wp->getId(),$product->getId()));
        }
    }
}
