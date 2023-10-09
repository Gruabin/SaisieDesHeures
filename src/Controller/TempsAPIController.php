<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TempsAPIController extends AbstractController
{
    #[Route('/temps/api', name: 'app_temps_api')]
    public function index(): Response
    {
        return $this->render('temps/temp.html.twig', [
            'controller_name' => 'TempsAPIController',
        ]);
    }
}
