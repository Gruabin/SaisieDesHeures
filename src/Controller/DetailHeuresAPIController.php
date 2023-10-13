<?php

namespace App\Controller;

use DateTime;
use DateTimeZone;
use App\Entity\DetailHeures;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DetailHeuresRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DetailHeuresAPIController extends AbstractController
{
    //* READ 
    #[Route('/api/get/detail_heures', name: 'api_get_detail_heures', methods: ['GET'])]
    public function get(DetailHeuresRepository $detailHeuresRepo): Response
    {
        // Récupérer les détails des heures correspondants depuis la base de données 
        $detailHeures = $detailHeuresRepo->findAll();
        // Vérifier si les détails des heures existent 
        if (!$detailHeures) {
            return new Response('Détails des heures non trouvés.', Response::HTTP_NOT_FOUND);
        }

        // Convertir les objets DetailHeures en tableau associatif 
        foreach ($detailHeures as $key => $value) {
            $detailHeuresData[$key] = [
                'id' => $value->getId(),
                'temps_main_oeuvre' => $value->getTempsMainOeuvre(),
                'date' => $value->getdate(),
                'type_heures' => $value->getTypeHeures(),
                'ordre' => $value->getOrdre(),
                'operation' => $value->getOperation(),
                'tache' => $value->getTache(),
                'activite' => $value->getActivite(),
                'centre_de_charge' => $value->getCentreDeCharge()
            ];
        }
        // Convertir les données en format JSON 
        $jsonData = json_encode($detailHeuresData);
        // Retourner une réponse avec les données des détails des heures au format JSON 
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }

    //* POST
    #[Route('/api/post/detail_heures', name: 'api_post_detail_heures', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return new Response('Aucune donnée soumise.', Response::HTTP_BAD_REQUEST);
        }

        // Valider les données entrantes
        $tempsMainOeuvre = isset($data['temps_main_oeuvre']) ? $data['temps_main_oeuvre'] : null;
        $typeHeures = isset($data['type_heures']) ? $data['type_heures'] : null;

        if ($tempsMainOeuvre === null || $typeHeures === null) {
            return new Response('Données manquantes.', Response::HTTP_BAD_REQUEST);
        }

        // Créer une nouvelle instance de l'entité DetailHeures
        $detailHeures = new DetailHeures();

        // Remplir les propriétés de l'entité avec les données reçues
        $now = new DateTime();
        $heure = DateTime::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d H:i:s'));
        $heure->setTimezone(new DateTimeZone('Europe/Paris'));

        $detailHeures->setDate($heure);
        $detailHeures->setTempsMainOeuvre($tempsMainOeuvre);
        $detailHeures->setTypeHeures($typeHeures);

        if (isset($data['ordre'])) {
            $detailHeures->setOrdre($data['ordre']);
        }
        if (isset($data['operation'])) {
            $detailHeures->setOperation($data['operation']);
        }
        if (isset($data['tache'])) {
            $detailHeures->setTache($data['tache']);
        }
        if (isset($data['activite'])) {
            $detailHeures->setActivite($data['activite']);
        }
        if (isset($data['centre_de_charge'])) {
            $detailHeures->setCentreDeCharge($data['centre_de_charge']);
        }

        // Enregistrer les détails des heures dans la base de données
        $entityManager->persist($detailHeures);
        $entityManager->flush();

        // Retourner une réponse indiquant que les détails des heures ont été créés avec succès
        return new Response('Détails des heures créés avec succès.', Response::HTTP_CREATED);
    }

    //* UPDATE 
    #[Route('/api/put/detail_heures', name: 'api_put_detail_heures', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT 
        $data = json_decode($request->getContent(), true);
        // Vérifier si l'ID est présent dans les données JSON 
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }
        $id = $data['id'];
        // Récupérer les détails des heures correspondants à l'ID depuis la base de données 
        $detailHeures = $entityManager->getRepository(DetailHeures::class)->findOneBy(['id' => $id]);
        // Vérifier si les détails des heures existent 
        if (!$detailHeures) {
            return new Response('Détails des heures non trouvés.', Response::HTTP_NOT_FOUND);
        }
        // Mettre à jour les propriétés des détails des heures avec les nouvelles données 
        $detailHeures->setTempsMainOeuvre($data['temps_main_oeuvre']);
        $detailHeures->setTypeHeures($data['type_heures']);

        if (isset($data['ordre'])) {
            $detailHeures->setOrdre($data['ordre']);
        }
        if (isset($data['operation'])) {
            $detailHeures->setOperation($data['operation']);
        }
        if (isset($data['tache'])) {
            $detailHeures->setTache($data['tache']);
        }
        if (isset($data['activite'])) {
            $detailHeures->setActivite($data['activite']);
        }
        if (isset($data['centre_de_charge'])) {
            $detailHeures->setCentreDeCharge($data['centre_de_charge']);
        }
        // Enregistrer les modifications dans la base de données 
        $entityManager->flush();
        // Retourner une réponse indiquant que les détails des heures ont été mis à jour avec succès 
        return new Response('Détails des heures mis à jour avec succès.', Response::HTTP_OK);
    }

    //* DELETE 
    #[Route('/api/delete/detail_heures', name: 'api_delete_detail_heures', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE 
        $data = json_decode($request->getContent(), true);
        // Vérifier si l'ID est présent dans les données JSON 
        if (!isset($data['id'])) {
            return new Response('ID manquant dans les données JSON.', Response::HTTP_BAD_REQUEST);
        }
        $id = $data['id'];
        // Récupérer les détails des heures correspondants à l'ID depuis la base de données 
        $detailHeures = $entityManager->getRepository(DetailHeures::class)->findOneBy(['id' => $id]);
        // Vérifier si les détails des heures existent 
        if (!$detailHeures) {
            return new Response('Détails des heures non trouvés.', Response::HTTP_NOT_FOUND);
        }
        // Supprimer les détails des heures de la base de données 
        $entityManager->remove($detailHeures);
        $entityManager->flush();
        // Retourner une réponse indiquant que les détails des heures ont été supprimés avec succès 
        return new Response('Détails des heures supprimés avec succès.', Response::HTTP_OK);
    }
}
