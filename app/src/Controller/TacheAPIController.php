<?php

namespace App\Controller;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class TacheAPIController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    // *READ
    #[Route('/api/get/tache/', name: 'api_get_tache', methods: ['GET'])]
    public function get(TacheRepository $tacheRepo): Response
    {
        // Récupérer la tache correspondante à l'ID depuis la base de données
        $tache = $tacheRepo->findAll();

        // Vérifier si la tache existe
        if (!$tache) {
            return new Response('Tache non trouvée.', Response::HTTP_NOT_FOUND);
        }

        // Convertir l'objet Tache en tableau associatif
        foreach ($tache as $key => $value) {
            $tacheData[$key] = [
                'id' => $value->getId(),
                'nom' => $value->getNomTache(),
            ];
        }

        // Convertir les données en format JSON
        $jsonData = json_encode($tacheData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données de la tache au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // * POST
    #[Route('/api/post/tache', name: 'api_post_tache', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (null === $data) {
            return new Response('Aucune donnée soumises.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité Tache
        $tache = new Tache();

        // Remplir les propriétés de l'entité avec les données reçues
        $tache->setid($data['id']);
        $tache->setNomTache($data['nom']);

        // Enregistrer la tache dans la base de données
        $entityManager->persist($tache);
        $entityManager->flush();

        $message = 'Tache créé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que la tache a été créé avec succès
        return new Response($message, Response::HTTP_CREATED);
    }

    // *UPDATE
    #[Route('/api/put/tache', name: 'api_put_tache', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer la tache correspondant à l'ID depuis la base de données
        $tache = $entityManager->getRepository(Tache::class)->findOneBy(['id' => $id]);

        // Vérifier si la tache existe
        if (!$tache) {
            return new Response('Tache non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour les propriétés de la tache avec les nouvelles données
        $tache->setNomTache($data['nom']);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        $message = 'Tache mis à jour avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que la tache a été mis à jour avec succès
        return new Response($message, Response::HTTP_OK);
    }

    // *DELETE
    #[Route('/api/delete/tache', name: 'api_delete_tache', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer la tâche correspondant à l'ID depuis la base de données
        $tache = $entityManager->getRepository(Tache::class)->findOneBy(['id' => $id]);

        // Vérifier si la tâche existe
        if (!$tache) {
            return new Response('Tache non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Supprimer la tâche de la base de données
        $entityManager->remove($tache);
        $entityManager->flush();

        $message = 'Tache supprimé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que la tâche a été supprimé avec succès
        return new Response($message, Response::HTTP_OK);
    }
}
