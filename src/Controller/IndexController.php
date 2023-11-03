<?php

namespace App\Controller;

use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\TacheRepository;
use App\Repository\TypeHeuresRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
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
    public function temps(TypeHeuresRepository $typeHeuresRepository, TacheRepository $tacheRepository, DetailHeuresRepository $detailHeuresRepository, CentreDeChargeRepository $CDGRepository): Response
    {
        // Rendre la vue 'temps/temps.html.twig' en passant les variables 'types', 'taches' et 'user'
        return $this->render('temps.html.twig', [
            'details' => $detailHeuresRepository->findAll(),
            'types' => $typeHeuresRepository->findAll(),
            'taches' => $tacheRepository->findAll(),
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
}
