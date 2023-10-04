<?php

namespace App\Controller;

use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AzureController extends AbstractController
{
    /**
     * Cette fonction effectue la connexion avec Azure
     * Ex: Si vous allez sur cette route, un formulaire microsoft vous demandera de vous connecter
     */
    #[Route('/connect/azure', name: 'connect_azure')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        return $clientRegistry
        ->getClient('azure')
        ->redirect([
            'openid', 'profile', 'email'
        ], []);
            
    }

    /**
     * Cette fonction permet de savoir si l'authentification à réussi
     * Ex: Après vous être connecté ci-dessus, vous serez rediriger sur cette route qui vous redirigera à son tour vers la route home
     */
    #[Route('/connect/azure/check', name: 'connect_azure_check', schemes:['http'])]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        if (!$this->getUser()) {
            return new JsonResponse(array('status' => false, 'message' => "User not found!"));
        } else {
            return $this->redirectToRoute('home');
        }

    }

    #[Route('/logout', name: 'disconnect_azure')]
    public function logout(Request $request, AuthenticationUtils $authenticationUtils)
    {
        // Effacer les informations d'authentification
        $authenticationUtils->clearAuthenticationToken();
        
        // Rediriger vers la page d'accueil ou la page de connexion
        return $this->redirectToRoute('home');
    }

    #[Route('/access-denied', name: 'access_denied')]
    public function accessDenied()
    {   
        // Rediriger vers la page d'erreur
        return $this->render('no_access.html.twig',[]);
    }
}