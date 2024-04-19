<?php

namespace App\Controller;

use App\Form\ConnexionType;
use App\Repository\EmployeRepository;
use App\Security\AuthSecurity;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * @property LoggerInterface $logger
 */
class ConnexionController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
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
            $user = $employeRepo->findOneBy(['id' => $form->getData()->getId()]);
            if ($user) {
                $userAuth->authenticateUser(
                    $user,
                    $authSecurity,
                    $request
                );

                $message = 'Connexion de '.$user->getNomEmploye();
                $this->logger->info($message);

                $session = $request->getSession();

                if ($employeRepo->estResponsable($user)) {
                    $user->setRoles(['ROLE_RESPONSABLE']);
                    $session->set('user_roles', $user->getRoles());

                    return $this->redirectToRoute('console');
                }

                $user->setRoles(['ROLE_EMPLOYE']);
                $session->set('user_roles', $user->getRoles());

                return $this->redirectToRoute('temps');
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
        $this->addFlash('success', 'Déconnexion de '.$this->getUser()->getNomEmploye());
        $this->logger->info($message);

        $tokenStorage = $this->container->get('security.token_storage');
        $tokenStorage->setToken(null);

        return $this->redirectToRoute('home');
    }
}
