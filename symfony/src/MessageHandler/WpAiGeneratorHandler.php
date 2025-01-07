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
        $apiKey = "gsk_xxxxx"; // TODO: update groq key
        $product=$this->managerRegistry->getRepository(Product::class)->find($message->productId);
        if($product instanceof Product){
            
            $response = $client->post('https://api.groq.com/openai/v1/chat/completions' , [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'mixtral-8x7b-32768',
                    'messages' => [
                        [
                            "role" => "system",
                            "content" => "act as web marketing agent , you should help me to have perfect copywriting for my ecommerce product description , you should respect seo guidelines and user friendly description, your response should be in the same language of the product. your response should respect this format {\"product_title\":\" new product title \",\"product_description\":\" new product description\"} . remove anything else from your response, your response should be  json format"
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
        $responseData = json_decode($response->getBody()->getContents(), true);

        $pattern = '/\{(?:[^{}]|(?R))*\}/';

        preg_match_all($pattern, $responseData["choices"][0]["message"]["content"], $matches);
        $matches=str_replace('\\','', $matches[0][0]);

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
