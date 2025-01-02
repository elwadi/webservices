<?php

namespace App\Controller;

use App\Entity\WpSite;
use App\Form\WpSiteType;
use App\Repository\WpSiteRepository;
use Automattic\WooCommerce\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/wp/site')]
final class WpSiteController extends AbstractController
{
    #[Route(name: 'app_wp_site_index', methods: ['GET'])]
    public function index(WpSiteRepository $wpSiteRepository): Response
    {
        return $this->render('wp_site/index.html.twig', [
            'wp_sites' => $wpSiteRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_wp_site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $wpSite = new WpSite();
        $form = $this->createForm(WpSiteType::class, $wpSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($wpSite);
            $entityManager->flush();

            $woocommerce = new Client(
                $wpSite->getWebsiteurl(),
                $wpSite->getCsKey(),
                $wpSite->getCsSecret(),
                [
                    'wp_api' => true,
                    'version' => 'wc/v3'
                ]
            );
    
            $data = [
                'name' => 'Product created',
                'topic' => 'product.created',
                'delivery_url' => 'https://webhook.site/227e0658-42d7-4b61-b297-caf4d3d8fe07'
            ];
            
            $webhookId=- $woocommerce->post('webhooks', $data);
            return $this->redirectToRoute('app_wp_site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wp_site/new.html.twig', [
            'wp_site' => $wpSite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_wp_site_show', methods: ['GET'])]
    public function show(WpSite $wpSite): Response
    {
        return $this->render('wp_site/show.html.twig', [
            'wp_site' => $wpSite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_wp_site_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WpSite $wpSite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WpSiteType::class, $wpSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_wp_site_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('wp_site/edit.html.twig', [
            'wp_site' => $wpSite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_wp_site_delete', methods: ['POST'])]
    public function delete(Request $request, WpSite $wpSite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$wpSite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($wpSite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_wp_site_index', [], Response::HTTP_SEE_OTHER);
    }
}
