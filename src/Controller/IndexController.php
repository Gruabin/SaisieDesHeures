<?php

namespace App\Controller;

use App\Entity\DetailHeures;
use App\Entity\Statut;
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
use Symfony\Component\Form\FormInterface as FormFormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * @property LoggerInterface           $logger
 * @property CentreDeChargeRepository  $CDGRepository
 * @property DetailHeuresRepository    $detailHeuresRepo
 * @property DetailHeureService        $detailHeureService
 * @property EmployeRepository         $employeRepo
 * @property Security                  $security
 * @property StatutRepository          $statutRepository
 * @property TacheRepository           $tacheRepository
 * @property TacheSpecifiqueRepository $tacheSpecifiqueRepo
 * @property TypeHeuresRepository      $typeHeuresRepo
 */
class IndexController extends AbstractController
{
    public function __construct(public LoggerInterface $logger, public CentreDeChargeRepository $CDGRepository, public DetailHeuresRepository $detailHeuresRepo, public DetailHeureService $detailHeureService, public EmployeRepository $employeRepo, public Security $security, public StatutRepository $statutRepository, public TacheRepository $tacheRepository, public TacheSpecifiqueRepository $tacheSpecifiqueRepo, public TypeHeuresRepository $typeHeuresRepo)
    {
    }

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(CacheInterface $cache): Response
    {
        return $cache->get(
            'index_page',
            function (ItemInterface $item) {
                $item->expiresAfter(43200);

                return $this->render(
                    'connexion/identification.html.twig',
                    [
                        'user' => $this->getUser(),
                    ]
                );
            }
        );
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
    public function temps(FavoriTypeHeureRepository $favoriTypeHeureRepo): Response
    {
        $nbHeures = $this->detailHeuresRepo->getNbHeures($this->getUser()->getUserIdentifier());
        if ($nbHeures >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";
            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        $favoriTypeHeure = $favoriTypeHeureRepo->findOneBy(['employe' => $this->getUser()]);
        $user = $this->getUser();

        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render(
            'temps.html.twig',
            [
                'details' => $this->detailHeuresRepo->findAllTodayUser(),
                'types' => $this->typeHeuresRepo->findAll(),
                'taches' => $this->tacheRepository->findAll(),
                'tachesSpe' => $this->tacheSpecifiqueRepo->findAllSite(),
                'CDG' => $this->CDGRepository->findAllUser(),
                'user' => $this->getUser(),
                'nbHeures' => $nbHeures,
                'favoriTypeHeure' => $favoriTypeHeure,
                'site' => substr((string) $user->getUserIdentifier(), 0, 2),
            ]
        );
    }

    // Affiche la page d'historique
    #[Route('/historique', name: 'historique')]
    public function historique(): Response
    {
        $nbHeures = $this->detailHeuresRepo->getNbHeures($this->getUser()->getUserIdentifier());
        if ($nbHeures >= 12) {
            $message = "Votre avez atteint votre limite d'heures journalières";

            $this->addFlash('warning', $message);
        }
        $this->detailHeureService->cleanLastWeek();

        return $this->render(
            'historique.html.twig',
            [
                'details' => $this->detailHeuresRepo->findAllTodayUser(),
                'user' => $this->getUser(),
                'nbHeures' => $nbHeures,
            ]
        );
    }

    // Affiche la page de console d'approbation
    #[Route('/console', name: 'console')]
    public function console(Request $request): Response
    {
        $session = $request->getSession();
        //  Utilisateur responsable par défaut
        if (!$session->has('responsablesId')) {
            $responsablesId = [];
            $responsablesId[0] = $this->getUser()->getUserIdentifier();
            $session->set('responsablesId', $responsablesId);
        }
        $user = $this->getUser();

        // Formulaire de filtre des responsables
        $formResponsable = $this->createForm(
            FiltreResponsableType::class,
            null,
            [
                'user' => $user,
                'data' => $this->employeRepo->findEmploye($session->get('responsablesId')),
            ]
        );

        $this->setResponsables($formResponsable, $request, $session);

        $dates = $this->detailHeuresRepo->findDatesDetail($session->get('responsablesId'));
        $tabEmployes = $this->employeRepo->findHeuresControle($session->get('responsablesId'));

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

        $formDate = $this->createForm(
            FiltreDateType::class,
            null,
            [
                'dates' => $dates,
                'data' => $data,
            ]
        );
        $formDate->handleRequest($request);

        $statutAnomalie = $this->statutRepository->getStatutAnomalie();
        $statutConforme = $this->statutRepository->getStatutConforme();

        if ($formDate->isSubmitted() && $formDate->isValid()) {
            $session->set('date', $formDate->get('date')->getData());
        }

        $tableau = $this->CreerTableau($tabEmployes, $statutConforme, $statutAnomalie, $session);

        $employes = $this->filtreEmploye($tableau['heures']);

        return $this->render(
            'console/console.html.twig',
            [
                'formResponsable' => $formResponsable->createView(),
                'formDate' => $formDate->createView(),
                'user' => $user,
                'site' => substr((string) $user->getUserIdentifier(), 0, 2),
                'nbAnomalie' => $tableau['nbAnomalie'],
                'employes' => $employes,
                'heures' => $tableau['heures'],
                'taches' => $this->tacheRepository->findAll(),
                'tachesSpe' => $this->tacheSpecifiqueRepo->findAllSite(),
                'CDG' => $this->CDGRepository->findAllUser(),
                'titrePage' => "Console d'approbation des heures",
            ]
        );
    }

    /**
     * Met en forme les données pour le tableau de la console.
     *
     * @param array<mixed>     $tabEmployes
     * @param Statut           $statutConforme
     * @param Statut           $statutAnomalie
     * @param SessionInterface $session
     *
     * @return array<mixed>
     *  */
    private function CreerTableau($tabEmployes, $statutConforme, $statutAnomalie, $session): array
    {
        $nbAnomalie = 0;
        $heures = [];
        $retour = [];
        foreach ($tabEmployes as $unEmploye) {
            foreach ($unEmploye->getDetailHeures() as $value) {
                if ((-1 === $session->get('date') || $value->getDate()->format('d-m-Y') === $session->get('date'))
                    && ($value->getStatut() === $statutConforme || $value->getStatut() === $statutAnomalie)
                ) {
                    array_push($heures, $value);
                    $nbAnomalie += ($value->getStatut() === $statutAnomalie) ? 1 : 0;
                }
            }
        }
        $retour['heures'] = $heures;
        $retour['nbAnomalie'] = $nbAnomalie;

        return $retour;
    }

    /**
     * Défini les responsables.
     *
     * @param FormFormInterface $formResponsable
     * @param Request           $request
     * @param SessionInterface  $session
     *  */
    private function setResponsables($formResponsable, $request, $session): void
    {
        $responsables = [];
        $formResponsable->handleRequest($request);
        if ($formResponsable->isSubmitted() && $formResponsable->isValid()) {
            $responsableSelected = $formResponsable->get('responsable')->getData();
            foreach ($responsableSelected as $key => $value) {
                $responsables[$key] = $value->getUserIdentifier();
            }
            $session->set('responsablesId', $responsables);
        }
    }

    /**
     * Filtre les employés possédant des heures.
     *
     * @param DetailHeures[] $heures
     *
     * @return array<mixed>
     */
    private function filtreEmploye($heures): array
    {
        $i = 0;
        $j = 0;
        $tab[$i] = null;
        $tab = [[]];
        foreach ($heures as $uneHeure) {
            if (!in_array($uneHeure->getEmploye()->getUserIdentifier(), $tab[$i])) {
                ++$i;
                $j = 0;
                $tab[$i]['id'] = $uneHeure->getEmploye()->getUserIdentifier();
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
