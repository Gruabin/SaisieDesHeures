<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\ConnexionType;
use Psr\Log\LoggerInterface;
use App\Security\AuthSecurity;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @property EntityManagerInterface $entityManager
 * @property LoggerInterface        $logger
 * @property Security               $security
 */
class ConnexionController extends AbstractController
{
    public function __construct(public EntityManagerInterface $entityManager, public LoggerInterface $logger, public Security $security) {}

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
            $user = $employeRepo->findOneBy(['id' => strtoupper((string) $form->getData()->getUserIdentifier())]);
            if ($user) {
                // Met à jour le role du manager
                if ($employeRepo->estResponsable($user) && 'ROLE_EMPLOYE' === $user->getRoles()[0]) {
                    $employe = $employeRepo->find($user->getUserIdentifier());
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
                $message = 'Connexion de ' . $user->getNomEmploye();
                $this->logger->info($message);

                return $this->redirectToRoute($route);
            }
        }

        return $this->render(
            'connexion/_formulaire.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    #[Route('/deconnexion', name: 'deconnexion')]
    public function logoutUser(): RedirectResponse
    {
        /**  @var Employe $user */
        $user =  $this->getUser();

        $message = 'Déconnexion de ' . $user->getNomEmploye();
        $this->addFlash('success', $message);
        $this->logger->info($message);

        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        return $this->redirectToRoute('home');
    }
}
