<?php

namespace App\Controller;

use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\TacheRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use App\Service\ExportService;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class IndexController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger,
    ) {
        $this->logger = $logger;
    }

    // Affiche la page d'identification
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Rendre la vue 'index/index.html.twig' en passant les variables 'controller_name' et 'user'
        return $this->render('identification.html.twig', [
            'controller_name' => 'IndexController',
            'user' => $this->getUser(),
        ]);
    }


    // Affiche la page de saisie des temps
    #[Route('/temps', name: 'temps')]
    public function temps(TypeHeuresRepository $typeHeuresRepo, DetailHeuresRepository $detailHeuresRepo, DetailHeureService $detailHeureService, TacheRepository $tacheRepository, DetailHeuresRepository $detailHeuresRepository, CentreDeChargeRepository $CDGRepository): Response
    {
        $nbHeures = $detailHeuresRepo->getNbHeures();
        if ($nbHeures['total'] >= 10) {
            $message = "Votre nombre d'heure est trop élevé";
            $this->addFlash('warning', $message);
        }
        $detailHeureService->cleanLastWeek();

        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render('temps.html.twig', [
            'details' => $detailHeuresRepository->findAllTodayUser(),
            'types' => $typeHeuresRepo->findAll(),
            'taches' => $tacheRepository->findAll(),
            'CDG' => $CDGRepository->findAll(),
            'user' => $this->getUser(),
            'nbHeures' => $nbHeures['total'],
        ]);
    }


    // Affiche la page d'historique
    #[Route('/historique', name: 'historique')]
    public function historique(DetailHeuresRepository $detailHeuresRepo, DetailHeureService $detailHeureService): Response
    {
        $nbHeures = $detailHeuresRepo->getNbHeures();
        if ($nbHeures['total'] >= 10) {
            $message = "Votre nombre d'heure est trop élevé";
            $this->addFlash('warning', $message);
        }
        $detailHeureService->cleanLastWeek();

        return $this->render('historique.html.twig', [
            'details' => $detailHeuresRepo->findAllTodayUser(),
            'user' => $this->getUser(),
            'nbHeures' => $nbHeures['total'],
        ]);
    }

    // Exporte le fichier Excel
    #[Route('/export', name: 'export')]
    public function export(ExportService $exportService): StreamedResponse
    {
        return $exportService->exportExcel();
    }
}
