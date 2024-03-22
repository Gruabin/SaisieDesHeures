<?php

namespace App\Controller;

use App\Repository\DetailHeuresRepository;
use App\Repository\StatutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @property LoggerInterface        $logger
 * @property EntityManagerInterface $entityManager
 */
class ConsoleController extends AbstractController
{
    public function __construct(
        LoggerInterface $logger,
        EntityManagerInterface $entityManager
    ) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
    }

    /**
     * Fonction pour approuver les lignes de détails des heures via une requête POST.
     * Vérifie le jeton CSRF, met à jour le statut des détails conformes à approuvé, et enregistre les modifications.
     * Retourne des messages de succès ou d'erreur en fonction du résultat.
     */
    #[Route('/api/post/approuverLigne', name: 'approuverLigne', methods: ['POST'])]
    public function approuverLigne(Request $request, DetailHeuresRepository $detailHeuresRepo, StatutRepository $statutRepo): Response
    {
        try {
            // Récupérer les données JSON envoyées dans la requête POST
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['token'];
            // Vérifie si le jeton CSRF est valide
            if ($this->isCsrfTokenValid('ligneToken'.$data['id'], $token)) {
                $statutApprouvé = $statutRepo->getStatutApprouve();
                $statutConforme = $statutRepo->getStatutConforme();
                // Parcourir les ID des détails envoyés
                foreach ($data['id'] as $ligne) {
                    $unDetail = $detailHeuresRepo->findOneBy(['id' => $ligne]);
                    // Vérifie si le statut du détail est conforme
                    if ($unDetail->getStatut() == $statutConforme) {
                        $unDetail->setStatut($statutApprouvé);
                        $this->entityManager->persist($unDetail);
                        $this->entityManager->flush();
                        $this->logger->info('Détail n°'.$ligne.' approuvé par '.$this->getUser()->getNomEmploye());
                    }
                }
                $message = 'Heures approuvées';
                $code = Response::HTTP_OK;
                $this->addFlash('success', $message);
            }
        } catch (\Throwable) {
            $message = 'Erreur lors de l\'approbation';
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->addFlash('error', $message);
        }

        return new Response($message, $code);
    }

    /**
     * Fonction pour supprimer les lignes de détails des heures via une requête POST.
     * Vérifie le jeton CSRF, met à jour le statut des détails à supprimé, et enregistre les modifications.
     * Retourne des messages de succès ou d'erreur en fonction du résultat.
     */
    #[Route('/api/post/supprimerligne', name: 'supprimerligne', methods: ['POST'])]
    public function supprimerligne(Request $request, DetailHeuresRepository $detailHeuresRepo, StatutRepository $statutRepo): Response
    {
        try {
            // Récupérer les données JSON envoyées dans la requête POST de manière sécurisée
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['token'] ?? '';

            // Vérifie si le jeton CSRF est valide
            if (!empty($token) && $this->isCsrfTokenValid('ligneToken_'.$data['id'], $token)) {
                $statutSupprime = $statutRepo->getStatutSupprime();
                $statutConforme = $statutRepo->getStatutConforme();
                $statutAnomalie = $statutRepo->getStatutAnomalie();
                $unDetail = $detailHeuresRepo->find($data['id']);

                if ($unDetail && in_array($unDetail->getStatut(), [$statutConforme, $statutAnomalie])) {
                    $unDetail->setStatut($statutSupprime);
                    $unDetail->setMotifErreur(null);
                    $this->entityManager->persist($unDetail);
                    $this->entityManager->flush();
                    $this->logger->info('Détail n°'.$data['id'].' Supprimé par '.$this->getUser()->getNomEmploye());

                    $message = 'Heure Supprimé';
                    $code = Response::HTTP_OK;
                } else {
                    $message = 'Statut du détail non conforme pour la suppression';
                    $code = Response::HTTP_BAD_REQUEST;
                }
            } else {
                $message = 'Jeton CSRF invalide';
                $code = Response::HTTP_UNAUTHORIZED;
            }
        } catch (\Throwable) {
            $message = 'Erreur lors de la suppression';
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new Response($message, $code);
    }
}
