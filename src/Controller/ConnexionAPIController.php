<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use App\Security\AuthSecurity;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @property LoggerInterface $logger
 */
class ConnexionAPIController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    #[Route('/api/post/connexion', name: 'api_post_connexion', methods: ['POST'])]
    public function loginUser(
        AuthSecurity $authSecurity,
        AuthenticationUtils $authenticationUtils,
        EmployeRepository $employeRepo,
        Request $request,
        UserAuthenticatorInterface $userAuth,
    ): Response {
        $error = $authenticationUtils->getLastAuthenticationError();
        if (!empty($error)) {
            $this->addFlash('error', 'Identification échoué');
        }

        // Récupérez le token de l'utilisateur depuis les données de la requête
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $token = $data['token'];

        if ($this->isCsrfTokenValid('loginToken', $token)) {
            // Récupérez l'ID de l'utilisateur depuis les données de la requête
            $userId = $data['id'];
            $user = $employeRepo->findOneBy(['id' => $userId]);
            if ($user) {
                $userAuth->authenticateUser(
                    $user,
                    $authSecurity,
                    $request
                );

                $message = 'Connexion de '.$user->getNomEmploye();
                $this->logger->info($message);

                $page = ($user->getResponsable()->count() > 0) ? '/console' : '/temps';

                return new RedirectResponse($page);
            } else {
                $message = 'Utilisateur introuvable';
                $this->logger->error($message);
            }
        } else {
            $message = 'Connexion échouée. Token invalide';
            $this->logger->error($message);
        }

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    #[Route('/api/post/deconnexion', name: 'api_post_deconnexion', methods: ['GET'])]
    public function logoutUser(): RedirectResponse
    {
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        $message = 'Déconnexion de '.$this->getUser()->getNomEmploye();
        $this->logger->info($message);

        return $this->redirectToRoute('home');
    }
}
