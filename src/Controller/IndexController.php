<?php

namespace App\Controller;

use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\OrdreRepository;
use App\Repository\TacheRepository;
use App\Repository\TypeHeuresRepository;
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
    public function temps(TypeHeuresRepository $typeHeuresRepository, TacheRepository $tacheRepository, OrdreRepository $ordreRepository, DetailHeuresRepository $detailHeuresRepository, CentreDeChargeRepository $CDGRepository): Response
    {
        // Rendre la vue 'temps/temps.html.twig' en passant les variables
        return $this->render('temps.html.twig', [
            'details' => $detailHeuresRepository->findAll(),
            'types' => $typeHeuresRepository->findAll(),
            'taches' => $tacheRepository->findAll(),
            'ordres' => $ordreRepository->findAll(),
            'CDG' => $CDGRepository->findAll(),
            'user' => $this->getUser(),
    ]);
    }

    #[Route('/historique', name: 'historique')]
    public function historique(DetailHeuresRepository $detailHeuresRepository): Response
    {
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
