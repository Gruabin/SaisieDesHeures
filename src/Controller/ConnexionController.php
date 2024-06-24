<?php

namespace App\Controller;

use App\Form\ConnexionType;
use App\Repository\EmployeRepository;
use App\Security\AuthSecurity;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @property EntityManagerInterface $entityManager
 * @property LoggerInterface        $logger
 * @property Security               $security
 */
#[Route('{_locale<%app.supported_locales%>}')]
class ConnexionController extends AbstractController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        LoggerInterface $logger,
        Security $security
    ) {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->security = $security;
    }

    #[Route('/_connexion', name: 'connexion')]
    public function connexion(
        Request $request,
        EmployeRepository $employeRepo,
        AuthSecurity $authSecurity,
        UserAuthenticatorInterface $userAuth,
    ): Response {
        $form = $this->createForm(ConnexionType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $employeRepo->findOneBy(['id' => strtoupper((string) $form->getData()->getId())]);
            if ($user) {
                // Met à jour le role du manager
                if ($employeRepo->estResponsable($user) && 'ROLE_EMPLOYE' === $user->getRoles()[0]) {
                    $employe = $employeRepo->find($user->getId());
                    $employe->setRoles(['ROLE_MANAGER']);
                    $this->entityManager->persist($employe);
                    $this->entityManager->flush();
                }
                // Authentification de l'utilisateur
                $userAuth->authenticateUser(
                    $user,
                    $authSecurity,
                    $request
                );
                // Redirection vers la page console ou temps
                $route = 'temps';
                if ('ROLE_MANAGER' === $user->getRoles()[0]) {
                    $route = 'console';
                }
                $message = 'Connexion de '.$user->getNomEmploye();
                $this->logger->info($message);

                return $this->redirectToRoute($route);
            }
        }

        return $this->render('connexion/_formulaire.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function logoutUser(): RedirectResponse
    {
        $message = 'Déconnexion de '.$this->getUser()->getNomEmploye();
        $this->addFlash('success', $message);
        $this->logger->info($message);

        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        return $this->redirectToRoute('home');
    }
}
