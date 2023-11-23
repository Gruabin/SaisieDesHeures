<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Service\ExportService;
use App\Repository\OrdreRepository;
use App\Repository\TacheRepository;
use App\Service\DetailHeureService;
use App\Repository\TypeHeuresRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\CentreDeChargeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @property LoggerInterface $logger
 */
class IndexController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        // Rendre la vue 'index/index.html.twig' en passant les variables 'controller_name' et 'user'
        return $this->render('identification.html.twig', [
            'controller_name' => 'IndexController',
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/temps', name: 'temps')]
    public function temps(TypeHeuresRepository $typeHeuresRepository, DetailHeureService $detailHeureService, TacheRepository $tacheRepository, OrdreRepository $ordreRepository, DetailHeuresRepository $detailHeuresRepository, CentreDeChargeRepository $CDGRepository): Response
    {
        $detailHeureService->cleanLastWeek();
        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render('temps.html.twig', [
            'details' => $detailHeuresRepository->findAllToday(),
            'types' => $typeHeuresRepository->findAll(),
            'taches' => $tacheRepository->findAll(),
            'ordres' => $ordreRepository->findAll(),
            'CDG' => $CDGRepository->findAll(),
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/historique', name: 'historique')]
    public function historique(DetailHeuresRepository $detailHeuresRepository, DetailHeureService $detailHeureService): Response
    {
        $detailHeureService->cleanLastWeek();
        return $this->render('historique.html.twig', [
            'details' => $detailHeuresRepository->findAllToday(),
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/export', name: 'export')]
    public function export(ExportService $exportService): StreamedResponse
    {
        return $exportService->exportExcel();
    }
}
