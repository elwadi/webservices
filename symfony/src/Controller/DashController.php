<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class DashController extends AbstractController
{
    #[Route('/dash', name: 'app_dash')]
    public function index(Request $request,ManagerRegistry $manager): Response
    {

        //ck_dbb70522a717d8e1062ad64e85f21b80f9d7553b
        //cs_5b9ce48a5718568cea743c813cc6e297d9c1922d
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
