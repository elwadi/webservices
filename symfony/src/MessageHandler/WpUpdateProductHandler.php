<?php

namespace App\MessageHandler;

use App\Entity\Product;
use App\Message\WpUpdateProduct;
use Automattic\WooCommerce\Client;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class WpUpdateProductHandler
{
    public function __construct(private ManagerRegistry $managerRegistry) {}

    public function __invoke(WpUpdateProduct $message): void
    {
        $product=$this->managerRegistry->getRepository(Product::class)->find($message->productId);
        if($product instanceof Product){
            
            $woocommerce = new Client(
                $product->getWebsite()->getWebsiteurl(),
                $product->getWebsite()->getCsKey(),
                $product->getWebsite()->getCsSecret(),
                [
                    'wp_api' => true,
                    'version' => 'wc/v3'
                ]
            );
            if($woocommerce){
                $updateData=[
                    'name'=>$product->getAiName(),
                    'description'=>$product->getAiDescription(),
                ];
                $woocommerce->put('products/'.$product->getProductId(), $updateData);
            }

        }
    }
}
