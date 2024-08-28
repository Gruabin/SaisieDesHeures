<?php

namespace App\Controller\API;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class EmployeAPIController extends AbstractController
{
    public function __construct(public LoggerInterface $logger)
    {
    }

    // *READ
    #[Route('/api/get/employe/{id}', name: 'api_get', methods: ['GET'])]
    public function get(string $id, EmployeRepository $employeRepo, Request $request): Response
    {
        if ('^^u6#h289SrB$!DxDDms55reFZcwWoY2e93TcseYf8^URbaZ%!CS^cHD^6YfyX!e4Lo@oPg3&u8b7dzA*Q9PYCdBRVRVGut3r2$JT2J9kU*FNKbmQ$@8oxtE5!mp7m8#' == $request->headers->get('X-API-Key')) {
            // Récupérer l'employé correspondant à l'ID depuis la base de données
            $employe = $employeRepo->findOneBy(['id' => $id]);

            // Vérifier si l'employé existe
            if (!$employe) {
                return new Response('Employe non trouvé.', Response::HTTP_NOT_FOUND);
            }

            // Convertir l'objet Employe en tableau associatif
            $employeData = [
                'id' => $employe->getUserIdentifier(),
                'nom' => $employe->getNomEmploye(),
            ];

            // Convertir les données en format JSON
            $jsonData = json_encode($employeData, JSON_THROW_ON_ERROR);

            // Retourner une réponse avec les données de l'employé au format JSON
            $response = new Response($jsonData);
            $response->headers->set('Content-Type', 'application/json');
            $response->setStatusCode(Response::HTTP_OK);
        } else {
            $response = new Response('Raté 🙃', Response::HTTP_UNAUTHORIZED);
        }

        return $response;
    }
}
