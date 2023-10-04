<?php

namespace App\Security;

use App\Entity\GestionAdmin;
use App\Entity\User;
use App\Repository\GestionAdminRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\azureUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class AzureAuthenticator extends OAuth2Authenticator implements AuthenticationEntryPointInterface
{
    private ClientRegistry $clientRegistry;
    private EntityManagerInterface $entityManager;
    private RouterInterface $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
    }

    /**
     * Cette fonction renvoie true alors la fonction authenticate sera appelée
     * @param Request $request
     * @return bool|null
     */
    public function supports(Request $request): ?bool
    {
        return $request->attributes->get('_route') === 'connect_azure_check';
    }

    /**
     * Cette fonction permet de traiter les données et de les utiliser. Elle vérifie également si l'utilisateur est déjà existant en base de donnée, si se n'est pas le cas elle l'ajoute.
     * @param Request $request
     * @return Passport
     */
    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('azure');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function() use ($accessToken, $client) {
                /** @var AzureUser $AzureUser */
                $AzureUser = $client->fetchUserFromToken($accessToken);
                // 1) have they logged in with Azure before? Easy!
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy(['uuid' => $AzureUser->getId()]);
                if ($existingUser) {
                    return $existingUser;
                }
                $user = new User();
                $user->setUuid($AzureUser->getId());
                $user->setNom($AzureUser->claim('family_name'));
                $user->setPrenom($AzureUser->claim('given_name'));
                $user->setEmail(strtolower($AzureUser->claim('upn')));
                $user->setRoles(array('ROLE_USER'));
                $this->entityManager->persist($user);
                $this->entityManager->flush();
                return $user;
            })
        );
    }

    /**
     * Si l'authentification réussi, l'utilisateur sera renvoyé sur la route home
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('home');
        return new RedirectResponse($targetUrl);
    }

    /**
     * Si l'authentification échoue, l'utilisateur sera informé avec un message d'erreur
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Cette fonction permet de rediriger l'utilisateur sur la route de connexion
     * Dès que l'utilisateur sera sur une route qu'il n'a pas le droit d'avoir accès il sera rediriger à cet endroit (Dans le cas de notre application toutes les routes sont par défaut interdite)
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/azure',
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

}