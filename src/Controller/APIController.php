<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class APIController extends AbstractController
{
    // *READ
    #[Route('/api/get/employe/{id}', name: 'api_get', methods: ['GET'])]
    public function get(string $id, EmployeRepository $employeRepo): Response
    {
        // Récupérer l'employé correspondant à l'ID depuis la base de données
        $employe = $employeRepo->findOneBy(['id' => $id]);

        // Vérifier si l'employé existe
        if (!$employe) {
            return new Response('Employe non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Convertir l'objet Employe en tableau associatif
        $employeData = [
            'id' => $employe->getId(),
            'nom' => $employe->getNomEmploye(),
            'centreDeChargeId' => $employe->getCentreDeCharge(),
        ];

        // Convertir les données en format JSON
        $jsonData = json_encode($employeData, JSON_THROW_ON_ERROR);

        // Retourner une réponse avec les données de l'employé au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // *READ
    #[Route('/api/get/employe2/{id}', name: 'api_get2', methods: ['GET'])]
    public function get2(string $id, EmployeRepository $employeRepo): Response
    {
        // Récupérer l'employé correspondant à l'ID depuis la base de données
        $qb = $employeRepo->createQueryBuilder('e');
        $qb->where($qb->expr()->like('e.id', ':premiersCaracteres'))
            ->setParameter('premiersCaracteres', $id.'%');
        $employe = $qb->getQuery()->getResult();
        // Convertir l'objet Employe en tableau associatif
        foreach ($employe as $key => $unEmploye) {
            $employeData[$key] = [
                'id' => $unEmploye->getId(),
                'nom' => $unEmploye->getNomEmploye(),
                'centreDeChargeId' => $unEmploye->getCentreDeCharge(),
            ];
        }

        // Convertir les données en format JSON
        $jsonData = json_encode($employeData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données de l'employé au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // * POST
    #[Route('/api/post/employe', name: 'api_post', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (null === $data) {
            return new Response('Aucune donnée soumises.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité Employe
        $employe = new Employe();

        // Remplir les propriétés de l'entité avec les données reçues
        $employe->setId($data['id']);
        $employe->setNomEmploye($data['nom']);
        $employe->setCentreDeCharge($data['centreDeChargeId']);

        // Enregistrer l'employé dans la base de données
        $entityManager->persist($employe);
        $entityManager->flush();

        // Retourner une réponse indiquant que l'employé a été créé avec succès
        return new Response('Employe créé avec succès.', Response::HTTP_CREATED);
    }

    // *UPDATE
    #[Route('/api/put/employe', name: 'api_put', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'employé correspondant à l'ID depuis la base de données
        $employe = $entityManager->getRepository(Employe::class)->findOneBy(['id' => $id]);

        // Vérifier si l'employé existe
        if (!$employe) {
            return new Response('Employe non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Mettre à jour les propriétés de l'employé avec les nouvelles données
        $employe->setNom($data['nom']);
        $employe->setNomEmploye($data['nom']);
        $employe->setCentreDeCharge($data['centreDeChargeId']);

        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Retourner une réponse indiquant que l'employé a été mis à jour avec succès
        return new Response('Employe mis à jour avec succès.', Response::HTTP_OK);
    }

    // *DELETE
    #[Route('/api/delete/employe', name: 'api_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        // Vérifier si l'ID est présent dans les données JSON
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }

        $id = $data['id'];

        // Récupérer l'employé correspondant à l'ID depuis la base de données
        $employe = $entityManager->getRepository(Employe::class)->findOneBy(['id' => $id]);

        // Vérifier si l'employé existe
        if (!$employe) {
            return new Response('Employe non trouvé.', Response::HTTP_NOT_FOUND);
        }

        // Supprimer l'employé de la base de données
        $entityManager->remove($employe);
        $entityManager->flush();

        // Retourner une réponse indiquant que l'employé a été supprimé avec succès
        return new Response('Employe supprimé avec succès.', Response::HTTP_OK);
    }
}
