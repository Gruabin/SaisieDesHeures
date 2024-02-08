<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface $logger
 */
class APIController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
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
                'id' => $employe->getId(),
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

    // *READ
    #[Route('/api/get/employe2/{id}', name: 'api_get2', methods: ['GET'])]
    public function get2(string $id, EmployeRepository $employeRepo, Request $request): Response
    {
        if ('*Q4mZWWphxjuBbcUU6YGWiLwddsFtQxBPDGwP#EwmB5KdmU^UgZYcV3h5puz@cg84YPYX&vmd%obs5$x9sRw58PUSk!iNZSfhzCssYB&5H#9fdFzFuaUUah7QVH8KenB' == $request->headers->get('X-API-Key')) {
            // Récupérer l'employé correspondant à l'ID depuis la base de données
            $qb = $employeRepo->createQueryBuilder('e');
            $qb->where($qb->expr()->like('e.id', ':premiersCaracteres'))
                ->setParameter('premiersCaracteres', $id . '%');
            $employe = $qb->getQuery()->getResult();
            // Convertir l'objet Employe en tableau associatif
            foreach ($employe as $key => $unEmploye) {
                $employeData[$key] = [
                    'id' => $unEmploye->getId(),
                    'nom' => $unEmploye->getNomEmploye(),
                ];
            }
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

        $message = 'Employe créé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'employé a été créé avec succès
        return new Response($message, Response::HTTP_CREATED);
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

        $message = 'Employe mis à jour avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'employé a été mis à jour avec succès
        return new Response($message, Response::HTTP_OK);
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

        $message = 'Employe supprimé avec succès.';
        $this->logger->info($message);

        // Retourner une réponse indiquant que l'employé a été supprimé avec succès
        return new Response($message, Response::HTTP_OK);
    }
}
