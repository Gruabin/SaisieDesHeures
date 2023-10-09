<?php

namespace App\Controller;

use App\Repository\TacheRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TempsAPIController extends AbstractController
{

        //READ
        #[Route('/api/get/tache/', name: 'api_get_tache', methods: ['GET'])]
        public function get2( TacheRepository $tacheRepo): Response
        {
            // Récupérer la tache correspondante à l'ID depuis la base de données
            $tache = $tacheRepo->findAll();
    
            // Vérifier si la tache existe
            if (!$tache) {
                return new Response('Tache non trouvée.', Response::HTTP_NOT_FOUND);
            }
    
            // Convertir l'objet Tache en tableau associatif
            foreach ($tache as $key => $value) {
                # code...
                $tacheData[$key] = [
                    'id' => $value->getId(),
                    'nom' => $value->getNomTache(),
                ];
            }
    
            // Convertir les données en format JSON
            $jsonData = json_encode($tacheData);
            // Retourner une réponse avec les données de l'employé au format JSON
            $response = new Response($jsonData);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
        }
}
