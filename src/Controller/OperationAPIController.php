<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class OperationAPIController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    // *READ
    #[Route('/api/get/operation', name: 'api_get_operation', methods: ['GET'])]
    public function get(OperationRepository $operationRepo): Response
    {
        // Récupérer l'operation correspondante à l'ID depuis la base de données
        $operation = $operationRepo->findAll();

        // Vérifier si l'operation existe
        if (!$operation) {
            return new Response('Operation non trouvée.', Response::HTTP_NOT_FOUND);
        }

        // Convertir l'objet Operation en tableau associatif
        foreach ($operation as $key => $value) {
            $operationData[$key] = [
                'id' => $value->getId(),
            ];
        }

        // Convertir les données en format JSON
        $jsonData = json_encode($operationData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données de l'operation au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // * POST
    #[Route('/api/post/operation', name: 'api_post_operation', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (null === $data) {
            return new Response('Aucune donnée soumises.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité Operation
        $operation = new Operation();

        // Enregistrer l'operation dans la base de données
        $entityManager->persist($operation);
        $entityManager->flush();

        $message = 'Operation créé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'operation a été créé avec succès
        return new Response($message, Response::HTTP_CREATED);
    }

    // *UPDATE
    #[Route('/api/put/operation', name: 'api_put_operation', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'operation correspondant à l'ID depuis la base de données
        $operation = $entityManager->getRepository(Operation::class)->findOneBy(['id' => $id]);

        // Vérifier si l'operation existe
        if (!$operation) {
            return new Response('Operation non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        $message = 'Operation mis à jour avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'operation a été mis à jour avec succès
        return new Response($message, Response::HTTP_OK);
    }

    // *DELETE
    #[Route('/api/delete/operation', name: 'api_delete_operation', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'opération correspondant à l'ID depuis la base de données
        $operation = $entityManager->getRepository(Operation::class)->findOneBy(['id' => $id]);

        // Vérifier si l'opération existe
        if (!$operation) {
            return new Response('Operation non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'opération de la base de données
        $entityManager->remove($operation);
        $entityManager->flush();

        $message = 'Operation supprimé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'opération a été supprimé avec succès
        return new Response($message, Response::HTTP_OK);
    }
}
