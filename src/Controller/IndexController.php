<?php

namespace App\Controller;

use App\Form\FiltreDateType;
use App\Form\FiltreResponsableType;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\EmployeRepository;
use App\Repository\FavoriTypeHeureRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @property LoggerInterface           $logger
 * @property CentreDeChargeRepository  $CDGRepository
 * @property DetailHeuresRepository    $detailHeuresRepository
 * @property DetailHeureService        $detailHeureService
 * @property EmployeRepository         $employeRepository
 * @property Security                  $security
 * @property StatutRepository          $statutRepository
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
        Security $security,
        StatutRepository $statutRepository,
        TacheRepository $tacheRepository,
        TacheSpecifiqueRepository $tacheSpecifiqueRepository,
        TypeHeuresRepository $typeHeuresRepo,
    ) {
        $this->logger = $logger;
        $this->CDGRepository = $CDGRepository;
        $this->detailHeuresRepository = $detailHeuresRepository;
        $this->detailHeureService = $detailHeureService;
        $this->employeRepository = $employeRepository;
        $this->security = $security;
        $this->statutRepository = $statutRepository;
        $this->tacheRepository = $tacheRepository;
        $this->tacheSpecifiqueRepository = $tacheSpecifiqueRepository;
        $this->typeHeuresRepo = $typeHeuresRepo;
    }

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(CacheInterface $cache): Response
    {
        return $cache->get('index_page', function (ItemInterface $item) {
            $item->expiresAfter(43200);

            return $this->render('connexion/identification.html.twig', [
                'user' => $this->getUser(),
            ]);
        });
    }

    // Redirige lors de l'accès refusé
    #[Route('/access_denied', name: 'access_denied')]
    public function accessDenied(): Response
    {
        $route = 'home';
        if ($this->isGranted('ROLE_EMPLOYE')) {
            $route = 'temps';
        } elseif ($this->isGranted('ROLE_RESPONSABLE') || $this->isGranted('ROLE_ADMIN')) {
            $route = 'console';
        }

        return $this->redirectToRoute($route);
    }

    // Affiche la page de saisie des temps
    #[Route('/temps', name: 'temps')]
    public function temps(FavoriTypeHeureRepository $favoriTypeHeureRepository): Response
    {
        $nbHeures = $this->detailHeuresRepository->getNbHeures($this->getUser()->getId());
        if ($nbHeures['total'] >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";
            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        $favoriTypeHeure = $favoriTypeHeureRepository->findOneBy(['employe' => $this->getUser()]);
        $user = $this->getUser();

        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render('temps.html.twig', [
            'details' => $this->detailHeuresRepository->findAllTodayUser(),
            'types' => $this->typeHeuresRepo->findAll(),
            'taches' => $this->tacheRepository->findAll(),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $this->CDGRepository->findAllUser(),
            'user' => $this->getUser(),
            'nbHeures' => $nbHeures['total'],
            'favoriTypeHeure' => $favoriTypeHeure,
            'site' => substr((string) $user->getId(), 0, 2),
        ]);
    }

    // Affiche la page d'historique
    #[Route('/historique', name: 'historique')]
    public function historique(): Response
    {
        $nbHeures = $this->detailHeuresRepository->getNbHeures($this->getUser());
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
        $session = $request->getSession();
        //  Utilisateur responsable par défaut
        if (!$session->has('responsablesId')) {
            $responsablesId[0] = $this->getUser()->getId();
            $session->set('responsablesId', $responsablesId);
        }
        $user = $this->getUser();
        $heures = [];

        // Formulaire de filtre des responsables
        $formResponsable = $this->createForm(FiltreResponsableType::class, null, [
            'user' => $user,
            'data' => $this->employeRepository->findEmploye($session->get('responsablesId')),
        ]);

        $this->setResponsables($formResponsable, $request, $session);

        $dates = $this->detailHeuresRepository->findDatesDetail($session->get('responsablesId'));
        $tabEmployes = $this->employeRepository->findHeuresControle($session->get('responsablesId'));
        $nbAnomalie = 0;

        //  Date par défaut
        if (!$session->has('date')) {
            $session->set('date', -1);
        }

        if (!in_array($session->get('date'), $dates)) {
            $data = [-1];
            $session->set('date', -1);
        } else {
            $data = [$session->get('date')];
        }

        $formDate = $this->createForm(FiltreDateType::class, null, [
            'dates' => $dates,
            'data' => $data,
        ]);
        $formDate->handleRequest($request);

        $statutAnomalie = $this->statutRepository->getStatutAnomalie();
        $statutConforme = $this->statutRepository->getStatutConforme();

        if ($formDate->isSubmitted() && $formDate->isValid()) {
            $session->set('date', $formDate->get('date')->getData());
        }

        foreach ($tabEmployes as $unEmploye) {
            foreach ($unEmploye->getDetailHeures() as $value) {
                if (
                    (-1 === $session->get('date') || $value->getDate()->format('d-m-Y') === $session->get('date'))
                    && ($value->getStatut() === $statutConforme || $value->getStatut() === $statutAnomalie)
                ) {
                    array_push($heures, $value);
                    $nbAnomalie += ($value->getStatut() === $statutAnomalie) ? 1 : 0;
                }
            }
        }

        $employes = $this->filtreEmploye($heures);

        return $this->render('console/console.html.twig', [
            'formResponsable' => $formResponsable->createView(),
            'formDate' => $formDate->createView(),
            'user' => $user,
            'site' => substr((string) $user->getId(), 0, 2),
            'nbAnomalie' => $nbAnomalie,
            'employes' => $employes,
            'heures' => $heures,
            'taches' => $this->tacheRepository->findAll(),
            'tachesSpe' => $this->tacheSpecifiqueRepository->findAllSite(),
            'CDG' => $this->CDGRepository->findAllUser(),
        ]);
    }

    // Défini les responsables
    private function setResponsables($formResponsable, $request, $session): void
    {
        $formResponsable->handleRequest($request);
        if ($formResponsable->isSubmitted() && $formResponsable->isValid()) {
            $responsableSelectionnes = $formResponsable->get('responsable')->getData();
            foreach ($responsableSelectionnes as $key => $value) {
                $responsables[$key] = $value->getId();
            }
            $session->set('responsablesId', $responsables);
        }
    }

    // Filtre les employés possédant des heures.
    private function filtreEmploye($heures): array
    {
        $i = 0;
        $j = 0;
        $tab[$i] = null;
        $tab = [[]];
        foreach ($heures as $uneHeure) {
            if (!in_array($uneHeure->getEmploye()->getId(), $tab[$i])) {
                ++$i;
                $j = 0;
                $tab[$i]['id'] = $uneHeure->getEmploye()->getId();
                $tab[$i]['nom'] = $uneHeure->getEmploye()->getNomEmploye();
                $tab[$i]['heures'][$j] = $uneHeure;
                $tab[$i]['total'] = $uneHeure->getTempsMainOeuvre();
            } else {
                $tab[$i]['heures'][$j] = $uneHeure;
                $tab[$i]['total'] += $uneHeure->getTempsMainOeuvre();
            }
            ++$j;
        }
        array_shift($tab);

        return $tab;
    }
}
