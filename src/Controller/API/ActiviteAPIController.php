<?php

namespace App\Controller\API;

use App\Entity\Activite;
use App\Repository\ActiviteRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class ActiviteAPIController extends AbstractController
{
    public function __construct(public LoggerInterface $logger)
    {
    }

    // *READ
    #[Route('/api/get/activite', name: 'api_get_activite', methods: ['GET'])]
    public function get(ActiviteRepository $activiteRepo): Response
    {
        $activiteData = [];
        // Récupérer l'activité correspondante à l'ID depuis la base de données
        $activite = $activiteRepo->findAll();
        // Vérifier si l'activité existe
        if (!$activite) {
            return new Response('Activité non trouvée.', Response::HTTP_NOT_FOUND);
        }
        // Convertir l'objet Activite en tableau associatif
        foreach ($activite as $key => $value) {
            $activiteData[$key] = [
                'id' => $value->getId(),
                'nom' => $value->getDescriptionActivite(),
            ];
        }
        // Convertir les données en format JSON
        $jsonData = json_encode($activiteData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données de l'activité au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }
}
