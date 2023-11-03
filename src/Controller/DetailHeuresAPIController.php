<?php

namespace App\Controller;

use App\Entity\DetailHeures;
use App\Entity\TypeHeures;
use App\Repository\ActiviteRepository;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\OperationRepository;
use App\Repository\OrdreRepository;
use App\Repository\TacheRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property TypeHeuresRepository     $typeHeuresRepository
 * @property OrdreRepository          $ordreRepository
 * @property ActiviteRepository       $activiteRepository
 * @property CentreDeChargeRepository $centreDeChargeRepository
 * @property OperationRepository      $operationRepository
 * @property TacheRepository          $tacheRepository
 */
class DetailHeuresAPIController extends AbstractController
{
    public function __construct(
        ActiviteRepository $activiteRepository,
        CentreDeChargeRepository $centreDeChargeRepository,
        OperationRepository $operationRepository,
        OrdreRepository $ordreRepository,
        TacheRepository $tacheRepository,
        TypeHeuresRepository $typeHeuresRepository
    ) {
        $this->typeHeuresRepository = $typeHeuresRepository;
        $this->ordreRepository = $ordreRepository;
        $this->activiteRepository = $activiteRepository;
        $this->centreDeChargeRepository = $centreDeChargeRepository;
        $this->operationRepository = $operationRepository;
        $this->tacheRepository = $tacheRepository;
    }

    // * READ
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
                'centre_de_charge' => $value->getCentreDeCharge(),
            ];
        }
        // Convertir les données en format JSON
        $jsonData = json_encode($detailHeuresData, JSON_THROW_ON_ERROR);
        // Retourner une réponse avec les données des détails des heures au format JSON
        $response = new Response($jsonData);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode(Response::HTTP_OK);

        return $response;
    }

    // * POST
    #[Route('/api/post/detail_heures', name: 'api_post_detail_heures', methods: ['POST'])]
    public function post(Request $request, EntityManagerInterface $entityManager, Security $security, DetailHeureService $detailHeureService): Response
    {
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $token = $data['token'];
        if (null === $data) {
            return new Response('Aucune donnée soumise.', Response::HTTP_BAD_REQUEST);
        }
        if ($this->isCsrfTokenValid('saisieToken', $token)) {
            // Valider les données entrantes
            $tempsMainOeuvre = $data['temps_main_oeuvre'] ?? null;
            $typeHeures = $data['type_heures'] ?? null;

            $typeHeures = $this->typeHeuresRepository->find($typeHeures);

            if (null === $tempsMainOeuvre || null === $typeHeures) {
                return new Response('Données manquantes.', Response::HTTP_BAD_REQUEST);
            }
            $detailHeures = $this->setDetailHeures($tempsMainOeuvre, $typeHeures, $security, $data);

            // Enregistrer les détails des heures dans la base de données
            $entityManager->persist($detailHeures);
            $entityManager->flush();
            $detailHeureService->cleanLastWeek();
            $this->addFlash('success', 'Détails des heures créés avec succès.');
        }

        // Retourner une réponse indiquant que les détails des heures ont été créés avec succès
        return new Response('Détails des heures créés avec succès.', Response::HTTP_CREATED);
    }

    // * UPDATE
    #[Route('/api/put/detail_heures', name: 'api_put_detail_heures', methods: ['PUT'])]
    public function put(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête PUT
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
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

        if (isset($data['ordre']) || '' != $data['ordre']) {
            $detailHeures->setOrdre($data['ordre']);
        }
        if (isset($data['operation']) || '' != $data['operation']) {
            $detailHeures->setOperation($data['operation']);
        }
        if (isset($data['tache']) || '' != $data['tache']) {
            $detailHeures->setTache($data['tache']);
        }
        if (isset($data['activite']) || '' != $data['activite']) {
            $detailHeures->setActivite($data['activite']);
        }
        if (isset($data['centre_de_charge']) || '' != $data['centre_de_charge']) {
            $detailHeures->setCentreDeCharge($data['centre_de_charge']);
        }
        // Enregistrer les modifications dans la base de données
        $entityManager->flush();

        // Retourner une réponse indiquant que les détails des heures ont été mis à jour avec succès
        return new Response('Détails des heures mis à jour avec succès.', Response::HTTP_OK);
    }

    // * DELETE
    #[Route('/api/delete/detail_heures', name: 'api_delete_detail_heures', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON envoyées dans la requête DELETE
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
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

    private function setDetailHeures(mixed $tempsMainOeuvre, TypeHeures $typeHeures, Security $security, array $data): DetailHeures
    {
        // Créer une nouvelle instance de l'entité DetailHeures
        $detailHeures = new DetailHeures();

        // Remplir les propriétés de l'entité avec les données reçues
        $now = new \DateTime();
        $heure = \DateTime::createFromFormat('Y-m-d H:i:s', $now->format('Y-m-d H:i:s'));
        $heure->setTimezone(new \DateTimeZone('Europe/Paris'));

        $detailHeures->setDate($heure);
        $detailHeures->setTempsMainOeuvre($tempsMainOeuvre);
        $detailHeures->setTypeHeures($typeHeures);
        $detailHeures->setEmploye($security->getUser());
        if (isset($data['ordre'])) {
            $ordre = $this->ordreRepository->find($data['ordre']);
            $detailHeures->setOrdre($ordre);
        } else {
            $detailHeures->setOrdre(null);
        }
        if (isset($data['operation'])) {
            $operation = $this->operationRepository->find($data['operation']);
            $detailHeures->setOperation($operation);
        } else {
            $detailHeures->setOperation(null);
        }
        if (isset($data['tache'])) {
            $tache = $this->tacheRepository->find($data['tache']);
            $detailHeures->setTache($tache);
        } else {
            $detailHeures->setTache(null);
        }
        if (isset($data['activite'])) {
            $activite = $this->activiteRepository->find($data['activite']);
            $detailHeures->setActivite($activite);
        } else {
            $detailHeures->setActivite(null);
        }
        if (isset($data['centre_de_charge'])) {
            $centreDeCharge = $this->centreDeChargeRepository->find($data['centre_de_charge']);
            $detailHeures->setCentreDeCharge($centreDeCharge);
        } else {
            $detailHeures->setCentreDeCharge(null);
        }

        return $detailHeures;
    }
}
