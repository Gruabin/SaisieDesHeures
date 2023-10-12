<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActiviteAPIController extends AbstractController
{
//*READ 
#[Route('/api/get/activite', name: 'api_get_activite', methods: ['GET'])] 
public function get(ActiviteRepository $activiteRepo): Response 
{ 
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
    $jsonData = json_encode($activiteData); 
    // Retourner une réponse avec les données de l'activité au format JSON 
    $response = new Response($jsonData); 
    $response->headers->set('Content-Type', 'application/json'); 
    $response->setStatusCode(Response::HTTP_OK); 
    return $response; 
} 
 
//* POST 
#[Route('/api/post/activite', name: 'api_post_activite', methods: ['POST'])] 
public function post(Request $request, EntityManagerInterface $entityManager): Response 
{ 
    // Récupérer les données JSON envoyées dans la requête POST 
    $data = json_decode($request->getContent(), true); 
    if ($data === null) { 
        return new Response('Aucune donnée soumise.', Response::HTTP_BAD_REQUEST); 
    } 
    // Créer une nouvelle instance de l'entité Activite 
    $activite = new Activite(); 
    // Remplir les propriétés de l'entité avec les données reçues 
    $activite->setDescriptionActivite($data['nom']); 
    // Enregistrer l'activité dans la base de données 
    $entityManager->persist($activite); 
    $entityManager->flush(); 
    // Retourner une réponse indiquant que l'activité a été créée avec succès 
    return new Response('Activité créée avec succès.', Response::HTTP_CREATED); 
} 
 
//*UPDATE 
#[Route('/api/put/activite', name: 'api_put_activite', methods: ['PUT'])] 
public function put(Request $request, EntityManagerInterface $entityManager): Response 
{ 
    // Récupérer les données JSON envoyées dans la requête PUT 
    $data = json_decode($request->getContent(), true); 
    // Vérifier si l'ID est présent dans les données JSON 
    if (!isset($data['id'])) { 
        return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST); 
    } 
    $id = $data['id']; 
    // Récupérer l'activité correspondante à l'ID depuis la base de données 
    $activite = $entityManager->getRepository(Activite::class)->findOneBy(['id' => $id]); 
    // Vérifier si l'activité existe 
    if (!$activite) { 
        return new Response('Activité non trouvée.', Response::HTTP_NOT_FOUND); 
    } 
    // Mettre à jour les propriétés de l'activité avec les nouvelles données 
    $activite->setDescriptionActivite($data['nom']); 
    // Enregistrer les modifications dans la base de données 
    $entityManager->flush(); 
    // Retourner une réponse indiquant que l'activité a été mise à jour avec succès 
    return new Response('Activité mise à jour avec succès.', Response::HTTP_OK); 
} 
 
//*DELETE 
#[Route('/api/delete/activite', name: 'api_delete_activite', methods: ['DELETE'])] 
public function delete(Request $request, EntityManagerInterface $entityManager): Response 
{ 
    // Récupérer les données JSON envoyées dans la requête DELETE 
    $data = json_decode($request->getContent(), true); 
    // Vérifier si l'ID est présent dans les données JSON 
    if (!isset($data['id'])) { 
        return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST); 
    } 
    $id = $data['id']; 
    // Récupérer l'activité correspondante à l'ID depuis la base de données 
    $activite = $entityManager->getRepository(Activite::class)->findOneBy(['id' => $id]); 
    // Vérifier si l'activité existe 
    if (!$activite) { 
        return new Response('Activité non trouvée.', Response::HTTP_NOT_FOUND); 
    } 
    // Supprimer l'activité de la base de données 
    $entityManager->remove($activite); 
    $entityManager->flush(); 
    // Retourner une réponse indiquant que l'activité a été supprimée avec succès 
    return new Response('Activité supprimée avec succès.', Response::HTTP_OK); 
}
}
