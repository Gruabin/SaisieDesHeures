<?php

namespace App\Controller;

use App\Form\FiltreResponsableType;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\EmployeRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface           $logger
 * @property CentreDeChargeRepository  $CDGRepository
 * @property DetailHeuresRepository    $detailHeuresRepository
 * @property DetailHeureService        $detailHeureService
 * @property EmployeRepository         $employeRepository
 * @property TacheRepository           $tacheRepository
 * @property TacheSpecifiqueRepository $tacheSpecifiqueRepository
 * @property TypeHeuresRepository      $typeHeuresRepo
 */
class IndexController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger,
        CentreDeChargeRepository $CDGRepository,
        DetailHeuresRepository $detailHeuresRepository,
        DetailHeureService $detailHeureService,
        EmployeRepository $employeRepository,
        TacheRepository $tacheRepository,
        TacheSpecifiqueRepository $tacheSpecifiqueRepository,
        TypeHeuresRepository $typeHeuresRepo,
    ) {
        $this->logger = $logger;
        $this->CDGRepository = $CDGRepository;
        $this->detailHeuresRepository = $detailHeuresRepository;
        $this->detailHeureService = $detailHeureService;
        $this->employeRepository = $employeRepository;
        $this->tacheRepository = $tacheRepository;
        $this->tacheSpecifiqueRepository = $tacheSpecifiqueRepository;
        $this->typeHeuresRepo = $typeHeuresRepo;
    }

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        if (null !== $session->get('user_roles')) {
            return $this->redirectToRoute('temps');
        }

        return $this->render('connexion/identification.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    // Affiche la page de saisie des temps
    #[Route('/temps', name: 'temps')]
    public function temps(Request $request): Response
    {
        $session = $request->getSession();
        if (null === $session->get('user_roles')) {
            return $this->redirectToRoute('home');
        }

        $nbHeures = $this->detailHeuresRepository->getNbHeures();
        if ($nbHeures['total'] >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";
            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render('temps.html.twig', [
            'details' => $this->detailHeuresRepository->findAllTodayUser(),
            'types' => $this->typeHeuresRepo->findAll(),
            'taches' => $this->tacheRepository->findAll(),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $this->CDGRepository->findAllUser(),
            'user' => $this->getUser(),
            'nbHeures' => $nbHeures['total'],
        ]);
    }

    // Affiche la page d'historique
    #[Route('/historique', name: 'historique')]
    public function historique(Request $request): Response
    {
        $session = $request->getSession();
        if (null === $session->get('user_roles')) {
            return $this->redirectToRoute('home');
        }

        $nbHeures = $this->detailHeuresRepository->getNbHeures();
        if ($nbHeures['total'] >= 10) {
            $message = "Votre nombre d'heure est trop élevé";
            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        return $this->render('historique.html.twig', [
            'details' => $this->detailHeuresRepository->findAllTodayUser(),
            'user' => $this->getUser(),
            'nbHeures' => $nbHeures['total'],
        ]);
    }

    // Affiche la page de console d'approbation
    #[Route('/console', name: 'console')]
    public function console(Request $request): Response
    {
        $user = $this->getUser();
        $session = $request->getSession();
        if ($session->get('user_roles') !== ['ROLE_RESPONSABLE']) {
            return $this->redirectToRoute('temps');
        }

        // Création d'un formulaire composé d'un select proposant la liste
        // de tous les responsables du même site que l'utilisateur connecté
        $form = $this->createForm(FiltreResponsableType::class, null, [
            'user' => $user,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $responsableSelectionnes = $form->get('responsable')->getData();

            $responsablesId = [];
            foreach ($responsableSelectionnes as $key => $value) {
                $responsablesId[$key] = $value->getId();
            }

            return $this->render('console/console.html.twig', [
                'form' => $form->createView(),
                'user' => $user,
                'site' => substr((string) $user->getId(), 0, 2),
                'nbAnomalie' => $this->detailHeuresRepository->findNbAnomalieResponsablesSelectionnes($responsablesId),
                'employes' => $this->employeRepository->findHeuresControleResponsablesSelectionnes($responsablesId),
                'taches' => $this->tacheRepository->findAll(),
                'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
                'CDG' => $this->CDGRepository->findAllUser(),
            ]);
        }

        return $this->render('console/console.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'site' => substr((string) $user->getId(), 0, 2),
            'nbAnomalie' => $this->detailHeuresRepository->findNbAnomalie(),
            'employes' => $this->employeRepository->findHeuresControle($user->getId()),
            'taches' => $this->tacheRepository->findAll(),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $this->CDGRepository->findAllUser(),
        ]);
    }
}
