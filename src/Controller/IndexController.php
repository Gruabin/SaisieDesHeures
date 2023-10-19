<?php

namespace App\Controller;

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
        return $this->render('connexion.html.twig', [
            'controller_name' => 'IndexController',
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/temps', name: 'temps')]
    public function temps(TypeHeuresRepository $typeRepo, TacheRepository $tacheRepo): Response
    {
        // Rendre la vue 'temps/temps.html.twig' en passant les variables 'types', 'taches' et 'user'
        return $this->render('temps.html.twig', [
            'types' => $typeRepo->findAll(),
            'taches' => $tacheRepo->findAll(),
            'user' => $this->getUser(),
        ]);
    }
}
