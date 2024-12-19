<?php

namespace App\Security;

use App\Entity\User;
use App\Message\SendMail;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiAuthenticator extends AbstractAuthenticator
{

    private $managerRegistry;
    private $bus;
    public function __construct(ManagerRegistry $managerRegistry,MessageBusInterface $bus)
    {
        $this->managerRegistry=$managerRegistry;
        $this->bus=$bus;
    }

    public function supports(Request $request): ?bool
    {
        return str_starts_with($request->attributes->get('_route'), 'api_');
    }

    public function authenticate(Request $request): Passport
    {
        $keyApi=$request->query->get('key');
        $user = $this->managerRegistry->getRepository(User::class)->findOneBy(['keyApi' => $keyApi]);
        $identifier= "";
        if($user instanceof User){
            if($user->getCounter()>0){
            
                $this->bus->dispatch(new SendMail($user->getId()));
                
                $identifier= $user->getEmail();
            }
        }
        return new SelfValidatingPassport(new UserBadge($identifier));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return  null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
