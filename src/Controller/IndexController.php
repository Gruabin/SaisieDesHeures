<?php

namespace App\Controller;

use App\Entity\TypeHeures;
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
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'user' => $this->getUser(),
        ]);
    }
    #[Route('/temps', name: 'temps')]
    public function temps(TypeHeuresRepository $typeRepo, TacheRepository $tacheRepo): Response
    {
        return $this->render('temps/temps.html.twig', [
            'types' => $typeRepo->findAll(),
            'taches' => $tacheRepo->findAll(),
            'user' => $this->getUser(),
        ]);
    }
}
