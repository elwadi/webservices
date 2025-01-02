<?php

namespace App\MessageHandler;

use App\Entity\Product;
use App\Message\WpAiGenerator;
use App\Message\WpUpdateProduct;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use GuzzleHttp\Client;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final class WpAiGeneratorHandler
{
    public function __construct(private ManagerRegistry $managerRegistry,private MessageBusInterface $bus) {}
    public function __invoke(WpAiGenerator $message): void
    {

        $client = new Client();
        $apiKey = "groq_key"; // TODO: update groq key
        $product=$this->managerRegistry->getRepository(Product::class)->find($message->productId);
        if($product instanceof Product){
            
            $response = $client->post('ADD_GROQ_URL' , [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-4o-mini', // TODO: Update model
                    'messages' => [
                        [
                            "role" => "system",
                            "content" => "act as web marketing agent , you should help me to have perfect copywriting for my ecommerce product description , you should respect seo guidelines and user friendly description, your response should be in french. your response should respect this json schema {\"product_title\":\"\",\"product_description\":\"\"} . remove anything else from your response, your response should be  json only",
                        ],
                        [
                            "role" => "user",
                            "content" => "product title :  ".$product->getProductName()."
                            product description : ".$product->getProductDescription()."."
                        ],
                    ],
                ],
            ]);
        }
        $pattern = '/\{(?:[^{}]|(?R))*\}/';

        $responseData = json_decode($response->getBody()->getContents(), true);
        preg_match_all($pattern, $responseData["choices"][0]["message"]["content"], $matches);

        if (!empty($matches[0])) {
           $content=json_decode($matches[0][0], true);
           $product->setAiName($content["product_title"]);
           $product->setAiDescription($content["product_description"]);
           $this->managerRegistry->getManager()->persist($product);
           $this->managerRegistry->getManager()->flush();
   
           $this->bus->dispatch(new WpUpdateProduct($product->getWebsite()->getId(),$product->getId()));
        } else {
            echo "No JSON objects found." . PHP_EOL;
        }

    }
}
