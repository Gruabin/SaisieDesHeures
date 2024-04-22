<?php

namespace App\Controller;

use App\Form\FiltreDateType;
use App\Form\FiltreResponsableType;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\EmployeRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use App\Service\ExportService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface           $logger
 * @property CentreDeChargeRepository  $CDGRepository
 * @property DetailHeuresRepository    $detailHeuresRepository
 * @property DetailHeureService        $detailHeureService
 * @property EmployeRepository         $employeRepository
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
        $this->statutRepository = $statutRepository;
        $this->tacheRepository = $tacheRepository;
        $this->tacheSpecifiqueRepository = $tacheSpecifiqueRepository;
        $this->typeHeuresRepo = $typeHeuresRepo;
    }

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('identification.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    // Affiche la page de saisie des temps
    #[Route('/temps', name: 'temps')]
    public function temps(): Response
    {
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
    public function historique(): Response
    {
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
        $session = $request->getSession();
        //  Utilisateur responsable par défaut
        if (!$session->has('responsablesId')) {
            $responsablesId[0] = $this->getUser()->getId();
            $session->set('responsablesId', $responsablesId);
        }
        $user = $this->getUser();
        $heures = [];
        if (!$this->employeRepository->estResponsable($user)) {
            return $this->redirectToRoute('temps');
        }
        $formResponsable = $this->createForm(FiltreResponsableType::class, null, [
            'user' => $user,
            'data' => $this->employeRepository->findEmploye($session->get('responsablesId')),
        ]);

        $this->setResponsables($formResponsable, $request, $session);

        $dates = $this->detailHeuresRepository->findDatesDetail($session->get('responsablesId'));
        $dates['Toutes les dates'] = -1;
        $tabEmployes = $this->employeRepository->findHeuresControle($session->get('responsablesId'));
        $nbAnomalie = 0;
        $formDate = $this->createForm(FiltreDateType::class, null, [
            'dates' => $dates,
        ]);
        $formDate->handleRequest($request);

        $statutAnomalie = $this->statutRepository->getStatutAnomalie();
        $statutConforme = $this->statutRepository->getStatutConforme();

        if (null != $tabEmployes) {
            if ($formDate->isSubmitted() && $formDate->isValid()) {
                if (-1 == $formDate->get('date')->getData()) {
                    foreach ($tabEmployes as $unEmploye) {
                        foreach ($unEmploye->getDetailHeures() as $value) {
                            if ($value->getStatut() == $statutConforme || $value->getStatut() == $statutAnomalie) {
                                array_push($heures, $value);
                                $nbAnomalie = ($value->getStatut() == $statutAnomalie) ? $nbAnomalie + 1 : $nbAnomalie;
                            }
                        }
                    }
                } else {
                    foreach ($tabEmployes as $unEmploye) {
                        foreach ($unEmploye->getDetailHeures() as $value) {
                            if (
                                $value->getDate()->format('d-m-Y') == $formDate->get('date')->getData()
                                && ($value->getStatut() == $statutConforme || $value->getStatut() == $statutAnomalie)
                                ) {
                                array_push($heures, $value);
                                $nbAnomalie = ($value->getStatut() == $statutAnomalie) ? $nbAnomalie + 1 : $nbAnomalie;
                            }
                        }
                    }
                }
            } else {
                foreach ($tabEmployes as $unEmploye) {
                    foreach ($unEmploye->getDetailHeures() as $value) {
                        if (
                            $value->getDate()->format('d-m-Y') == date('d-m-Y')
                            && ($value->getStatut() == $statutConforme || $value->getStatut() == $statutAnomalie)
                        ) {
                            array_push($heures, $value);
                            $nbAnomalie = ($value->getStatut() == $statutAnomalie) ? $nbAnomalie + 1 : $nbAnomalie;
                        }
                    }
                }
            }
        }

        $employes = $this->FiltreEmploye($heures);
        
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
    public function setResponsables($formResponsable, $request, $session): void
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
    public function FiltreEmploye($heures): array
    {
        $employesFiltre = [];
        foreach ($heures as $uneHeure) {
            if (!in_array($uneHeure->getEmploye(), $employesFiltre)) {
                array_push($employesFiltre, $uneHeure->getEmploye());
            }
        }
        return $employesFiltre;
    }


    // Exporte le fichier Excel
    #[Route('/export', name: 'export')]
    public function export(ExportService $exportService): StreamedResponse
    {
        return $exportService->exportExcel();
    }
}
