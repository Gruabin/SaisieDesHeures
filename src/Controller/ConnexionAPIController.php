<?php

namespace App\Controller;

use App\Repository\EmployeRepository;
use App\Security\AuthSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        EntityManagerInterface $entityManager,
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
            // Vous pouvez vérifier l'existence de l'utilisateur en fonction de son ID ici
            // Assurez-vous d'adapter cette logique à votre propre système
            $user = $employeRepo->findOneBy(['id' => $userId]);

            if ($user) {
                $userAuth->authenticateUser(
                    $user,
                    $authSecurity,
                    $request
                );

                $message = 'Connexion réussi.';
                $this->logger->info($message);

                return $this->json(['message' => 'ID OK'], Response::HTTP_OK);
            }
        }

        $message = 'Connexion échouée.';
        $this->logger->error($message);
        $this->addFlash('error', $message);

        $message = $this->renderView('alert.html.twig');

        return $this->json(['message' => $message], Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/post/deconnexion', name: 'api_post_deconnexion', methods: ['GET'])]
    public function logoutUser(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        $message = 'Déconnexion réussi.';
        $this->logger->info($message);

        return $this->redirectToRoute('home');
    }

    #[Route('/home', name: 'home_page', methods: ['GET'])]
    public function securedPage(): Response
    {
        // Cette action est accessible uniquement si l'utilisateur est connecté
        return $this->json(['message' => 'Page sécurisée']);
    }
}
