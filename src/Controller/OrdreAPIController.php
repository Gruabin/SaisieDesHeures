<?php

namespace App\Controller;

use App\Entity\Ordre;
use App\Repository\OrdreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class OrdreAPIController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    // READ
    #[Route('/api/get/ordre/{id}', name: 'api_get_ordre', methods: ['GET'])]
    public function get2(OrdreRepository $ordreRepo): Response
    {
        // Récupérer l'ordre correspondante à l'ID depuis la base de données
        $ordre = $ordreRepo->findAll();

        // Vérifier si l'ordre existe
        if (!$ordre) {
            return new Response('Ordre non trouvée.', Response::HTTP_NOT_FOUND);
        }

        // Convertir l'objet Ordre en tableau associatif
        foreach ($ordre as $key => $value) {
            $ordreData[$key] = [
                'id' => $value->getId(),
            ];
        }

        // Convertir les données en format JSON
        $jsonData = json_encode($ordreData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données de l'a 'ordre au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // * POST
    #[Route('/api/post/ordre', name: 'api_post_ordre', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (null === $data) {
            return new Response('Aucune donnée soumises.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité Ordre
        $ordre = new Ordre();

        // Remplir les propriétés de l'entité avec les données reçues
        $ordre->setid($data['id']);

        // Enregistrer l'ordre dans la base de données
        $entityManager->persist($ordre);
        $entityManager->flush();

        $message = 'Ordre créé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'ordre a été créé avec succès
        return new Response($message, Response::HTTP_CREATED);
    }

    // *UPDATE
    #[Route('/api/put/ordre', name: 'api_put_ordre', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'ordre correspondant à l'ID depuis la base de données
        $ordre = $entityManager->getRepository(Ordre::class)->findOneBy(['id' => $id]);

        // Vérifier si l'ordre existe
        if (!$ordre) {
            return new Response('Ordre non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        $message = 'Ordre mis à jour avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'ordre a été mis à jour avec succès
        return new Response($message, Response::HTTP_OK);
    }

    // *DELETE
    #[Route('/api/delete/ordre', name: 'api_delete_ordre', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'ordre correspondant à l'ID depuis la base de données
        $ordre = $entityManager->getRepository(Ordre::class)->findOneBy(['id' => $id]);

        // Vérifier si l'ordre existe
        if (!$ordre) {
            return new Response('Ordre non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'ordre de la base de données
        $entityManager->remove($ordre);
        $entityManager->flush();

        $message = 'Ordre supprimé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'ordre a été supprimé avec succès
        return new Response($message, Response::HTTP_OK);
    }
}
