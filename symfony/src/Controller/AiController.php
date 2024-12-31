<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Exception;
use GuzzleHttp\Client;

class AiController extends AbstractController
{
    #[Route('/ai', name: 'app_ai')]
    public function index(): Response
    {
     
        $client = new Client();
        $apiKey="gsk_xxxxxx";

    try {
        $response = $client->post('https://api.groq.com/openai/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                //'model' => 'mixtral-8x7b-32768',
                'model' => 'llama3-groq-70b-8192-tool-use-preview',
                'messages' => [
                    [
                        "role" => "system",
                        "content" => "act as web marketing agent , you should help me to have perfect copywriting for my ecommerce product description , you should respect seo guidelines and user friendly description, your response should be in french. your response should respect this json schema {\"product_title\":\"\",\"product_description\":\"\"} . remove anything else from your response, your response should be  json only",
                    ],
                    [
                        "role" => "user",
                        "content" => "product title : CITYTEK - Smart watch Tracker - BTH 5.0 MF109313 - BOX3 - Noir . product description : La smartwatch tracker BTH 5.0 de CityTek Box3 est un compagnon de fitness et de santé idéal pour suivre votre activité quotidienne et votre bien-être.
Dotée de la technologie Bluetooth 5.0, cette montre connectée offre une connectivité stable et rapide avec votre smartphone, vous permettant de recevoir des notifications, des appels, des messages et d'autres alertes directement sur votre poignet.
Elle dispose également de nombreuses fonctionnalités de suivi de la santé, telles que le suivi de la fréquence cardiaque, du sommeil, des pas, des calories brûlées et bien plus encore."
                    ],
                ],
            ],
        ]);
        
        $responseData = json_decode($response->getBody()->getContents(), true);
        dd($responseData);

        return new Response();
    } catch (Exception $e) {
        return new Response();
    }
        
    }
}