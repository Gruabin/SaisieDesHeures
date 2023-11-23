<?php

namespace App\Controller;

use App\Entity\DetailHeures;
use App\Entity\Ordre;
use App\Entity\TypeHeures;
use App\Repository\ActiviteRepository;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\OrdreRepository;
use App\Repository\TacheRepository;
use App\Repository\TypeHeuresRepository;
use App\Service\DetailHeureService;
use App\Service\ExportService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property TypeHeuresRepository     $typeHeuresRepository
 * @property OrdreRepository          $ordreRepository
 * @property ActiviteRepository       $activiteRepository
 * @property CentreDeChargeRepository $centreDeChargeRepository
 * @property TacheRepository          $tacheRepository
 * @property LoggerInterface          $logger
 */
class DetailHeuresAPIController extends AbstractController
{
    public function __construct(
        ActiviteRepository $activiteRepository,
        CentreDeChargeRepository $centreDeChargeRepository,
        OrdreRepository $ordreRepository,
        TacheRepository $tacheRepository,
        TypeHeuresRepository $typeHeuresRepository,
        LoggerInterface $logger,
    ) {
        $this->typeHeuresRepository = $typeHeuresRepository;
        $this->ordreRepository = $ordreRepository;
        $this->activiteRepository = $activiteRepository;
        $this->centreDeChargeRepository = $centreDeChargeRepository;
        $this->tacheRepository = $tacheRepository;
        $this->logger = $logger;
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
        $detailHeuresData = [];
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
        if ($this->isCsrfTokenValid('saisieToken', $token) || (null == $data)) {
            // Valider les données entrantes
            $tempsMainOeuvre = $data['temps_main_oeuvre'] ?? null;
            $typeHeures = $data['type_heures'] ?? null;

            $typeHeures = $this->typeHeuresRepository->find($typeHeures);

            if (null === $tempsMainOeuvre || null === $typeHeures) {
                $this->addFlash('error', 'Données manquantes.');

                return new Response('Données manquantes.', Response::HTTP_BAD_REQUEST);
            }
            $detailHeures = $this->setDetailHeures($tempsMainOeuvre, $typeHeures, $security, $entityManager, $data);
            if (!$detailHeures) {
                $message = "L'ajout de saisie des heures a échoué.";
                $this->logger->error($message);

                return new Response($message, Response::HTTP_BAD_REQUEST);
            }

            // Enregistrer les détails des heures dans la base de données
            $entityManager->persist($detailHeures);
            $entityManager->flush();
            $detailHeureService->cleanLastWeek();

            $message = 'Saisie des heures créé avec succès.';
            $this->logger->info($message);
            $this->addFlash('success', $message);

            // Retourner une réponse indiquant que les détails des heures ont été créés avec succès
            return new Response($message, Response::HTTP_CREATED);
        }

        return new Response('Aucune donnée soumise.', Response::HTTP_BAD_REQUEST);
    }

    private function setDetailHeures(mixed $tempsMainOeuvre, TypeHeures $typeHeures, Security $security, EntityManagerInterface $entityManager, array $data): ?DetailHeures
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

        if (!empty($data['ordre'])) {
            $ordre = new Ordre();
            $ordre->setId($data['ordre']);
            $entityManager->persist($ordre);
            $entityManager->flush();
            $detailHeures->setOrdre($ordre);
        }
        if (!empty($data['operation'])) {
            $detailHeures->setOperation($data['operation']);
        }
        if (!empty($data['tache'])) {
            $tache = $this->tacheRepository->find($data['tache']);
            if (!$tache) {
                $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Tache manquant');

                return null;
            }
            $detailHeures->setTache($tache);
        }
        if (!empty($data['activite'])) {
            $activite = $this->activiteRepository->find($data['activite']);
            if (!$activite) {
                $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Activite manquant');

                return null;
            }
            $detailHeures->setActivite($activite);
        }
        if (!empty($data['centre_de_charge'])) {
            if (1 == $typeHeures->getId()) {
                $centreDeCharge = $this->centreDeChargeRepository->find($data['centre_de_charge']);
                if (!$centreDeCharge) {
                    $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Centre de charge manquant');

                    return null;
                }
                $detailHeures->setCentreDeCharge($centreDeCharge);
            }
        }

        return $detailHeures;
    }

    // * READ
    #[Route('/api/get/export', name: 'api_get_export', methods: ['GET'])]
    public function export(ExportService $exportService): StreamedResponse
    {
        try {
            $message = "L'export saisi des heures créés avec succès.";
            $this->logger->info($message);

            return $exportService->exportExcel();
        } catch (\Exception $exception) {
            $message = "L'export saisi des heures a échoué.";
            $this->logger->error($message);
            $this->logger->error($exception);
        }
    }
}
