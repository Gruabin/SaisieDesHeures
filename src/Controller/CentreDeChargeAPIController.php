<?php

namespace App\Controller;

use App\Entity\CentreDeCharge;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CentreDeChargeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CentreDeChargeAPIController extends AbstractController
{
    //*READ 
    #[Route('/api/get/centre_de_charge', name: 'api_get_centre_de_charge', methods: ['GET'])]
    public function get(CentreDeChargeRepository $centreDeChargeRepo): Response
    {
        // Récupérer le centre de charge correspondant à l'ID depuis la base de données 
        $centreDeCharge = $centreDeChargeRepo->findAll();
        // Vérifier si le centre de charge existe 
        if (!$centreDeCharge) {
            return new Response('Centre de charge non trouvé.', Response::HTTP_NOT_FOUND);
        }
        // Convertir l'objet CentreDeCharge en tableau associatif 
        foreach ($centreDeCharge as $key => $value) {
            $centreDeChargeData[$key] = [
                'id' => $value->getId(),
                'nom' => $value->getDescriptionCDG(),
            ];
        }
        // Convertir les données en format JSON 
        $jsonData = json_encode($centreDeChargeData);
        // Retourner une réponse avec les données du centre de charge au format JSON 
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    //* POST 
    #[Route('/api/post/centre_de_charge', name: 'api_post_centre_de_charge', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST 
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new Response('Aucune donnée soumise.', Response::HTTP_BAD_REQUEST);
        }
        // Créer une nouvelle instance de l'entité CentreDeCharge 
        $centreDeCharge = new CentreDeCharge();
        // Remplir les propriétés de l'entité avec les données reçues 
        $centreDeCharge->setId($data['id']);
        $centreDeCharge->setDescriptionCDG($data['nom']);
        // Enregistrer le centre de charge dans la base de données 
        $entityManager->persist($centreDeCharge);
        $entityManager->flush();
        // Retourner une réponse indiquant que le centre de charge a été créé avec succès 
        return new Response('Centre de charge créé avec succès.', Response::HTTP_CREATED);
    }

    //*UPDATE 
    #[Route('/api/put/centre_de_charge', name: 'api_put_centre_de_charge', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT 
        $data = json_decode($request->getContent(), true);
        // Vérifier si l'ID est présent dans les données JSON 
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }
        $id = $data['id'];
        // Récupérer le centre de charge correspondant à l'ID depuis la base de données 
        $centreDeCharge = $entityManager->getRepository(CentreDeCharge::class)->findOneBy(['id' => $id]);
        // Vérifier si le centre de charge existe 
        if (!$centreDeCharge) {
            return new Response('Centre de charge non trouvé.', Response::HTTP_NOT_FOUND);
        }
        // Mettre à jour les propriétés du centre de charge avec les nouvelles données 
        $centreDeCharge->setDescriptionCDG($data['nom']);
        // Enregistrer les modifications dans la base de données 
        $entityManager->flush();
        // Retourner une réponse indiquant que le centre de charge a été mis à jour avec succès 
        return new Response('Centre de charge mis à jour avec succès.', Response::HTTP_OK);
    }

    //*DELETE 
    #[Route('/api/delete/centre_de_charge', name: 'api_delete_centre_de_charge', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE 
        $data = json_decode($request->getContent(), true);
        // Vérifier si l'ID est présent dans les données JSON 
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }
        $id = $data['id'];
        // Récupérer le centre de charge correspondant à l'ID depuis la base de données 
        $centreDeCharge = $entityManager->getRepository(CentreDeCharge::class)->findOneBy(['id' => $id]);
        // Vérifier si le centre de charge existe 
        if (!$centreDeCharge) {
            return new Response('Centre de charge non trouvé.', Response::HTTP_NOT_FOUND);
        }
        // Supprimer le centre de charge de la base de données 
        $entityManager->remove($centreDeCharge);
        $entityManager->flush();
        // Retourner une réponse indiquant que le centre de charge a été supprimé avec succès 
        return new Response('Centre de charge supprimé avec succès.', Response::HTTP_OK);
    }
}
