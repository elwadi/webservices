<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashController extends AbstractController
{
    #[Route('/dash', name: 'app_dash')]
    public function index(ManagerRegistry $manager): Response
    {
        $data=[];

        $data['companies']=$manager->getRepository(Company::class)->findAll();

        return $this->render('dash/index.html.twig', $data);
    }

    #[Route('/dash/create', name: 'app_dash_create')]
    public function create(Request $request, ManagerRegistry $manager): Response
    {
        if($request->isMethod('POST')){
            $company=new Company();
            $form=$this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $company=$form->getData();
                $manager->getManager()->persist($company);// 1
                $manager->getManager()->flush();
            }

            return $this->redirectToRoute('app_dash');
        }

        $data=[];
        $data['form']=$this->createForm(CompanyType::class)->createView();
        return $this->render('dash/create.html.twig', $data);
    }

    #[Route('/dash/update/{id}', name: 'app_dash_update')]
    public function update(Company $company,Request $request, ManagerRegistry $manager): Response
    {
        if($request->isMethod('POST')){
            $form=$this->createForm(CompanyType::class, $company);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $company=$form->getData();
                $manager->getManager()->persist($company);// 1
                $manager->getManager()->flush();
            }

            return $this->redirectToRoute('app_dash');
        }


        $data=[];
        $data['form']=$this->createForm(CompanyType::class, $company)->createView();
        return $this->render('dash/update.html.twig', $data);
    }
}
