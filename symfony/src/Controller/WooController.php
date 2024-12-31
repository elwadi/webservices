<?php

namespace App\Controller;

use Automattic\WooCommerce\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class WooController extends AbstractController
{
    #[Route('/woo', name: 'app_woo')]
    public function index(): Response
    {

        //ck_dbb70522a717d8e1062ad64e85f21b80f9d7553b
        //cs_5b9ce48a5718568cea743c813cc6e297d9c1922d
        $woocommerce = new Client(
            'https://desirable-dove-1d539f.instawp.xyz',
            'ck_dbb70522a717d8e1062ad64e85f21b80f9d7553b',
            'cs_5b9ce48a5718568cea743c813cc6e297d9c1922d',
            [
                'wp_api' => true,
                'version' => 'wc/v3'
            ]
        );

        $data=$woocommerce->get('products');
        dd($data);
        $updateData=[
            'name'=>'updated name',
        ];
        $woocommerce->put('products/59', $updateData);


        return $this->render('woo/index.html.twig', [
            'controller_name' => 'WooController',
        ]);
    }
    //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
}
