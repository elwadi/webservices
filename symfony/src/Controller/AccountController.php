<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\LogMessage;
use App\Form\AccountType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/account', name: 'app_account')]
    public function index(ManagerRegistry $manager): Response
    {
        $data=[];
        $data['accounts']=$manager->getRepository(Account::class)->findAll();
        return $this->render('account/index.html.twig', $data);
    }

    #[Route('/account/create', name: 'app_account_create')]
    public function create(Request $request,ManagerRegistry $manager): Response
    {
        if($request->isMethod('POST')){
            $account=new Account();
            $form=$this->createForm(AccountType::class, $account);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $account=$form->getData();
                $manager->getManager()->persist($account);// 1

                $logMessage=new LogMessage();
                $logMessage->setAccount($account);
                $logMessage->setMessage('Account created');
                
                $manager->getManager()->persist($logMessage);// 2
                $manager->getManager()->flush();
            }

            return $this->redirectToRoute('app_account');
        }

        $data=[];
        $data['form']=$this->createForm(AccountType::class)->createView();
        return $this->render('account/create.html.twig', $data);
    }
}
