<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\WpSite;
use App\Message\WpImportProduct;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class WebhookController extends AbstractController
{
    #[Route('/webhook', name: 'app_webhook',methods: ['POST'])]
    public function index(Request $request,ManagerRegistry $manager,MessageBusInterface $bus): JsonResponse
    {
        $storeUrl = $request->headers->get('x-wc-webhook-source');
        $currentStore=$manager->getManager()->getRepository(WpSite::class)->findOneBy(['websiteurl' => $storeUrl]);
        if($currentStore instanceof WpSite){
            $data = json_decode($request->getContent(), true);
            $productId = $data['id'];
            $product = $manager->getManager()->getRepository(Product::class)->findOneBy(['productId' => $productId, 'website' => $currentStore]);
            if(!$product instanceof Product){
                $bus->dispatch(new WpImportProduct($currentStore->getId(),$productId,$data['name'],$data['description']));
            }

        }

        return new JsonResponse();
    }
}
