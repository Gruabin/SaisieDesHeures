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

    #[Route('/api/post/approuver', name: 'approuver', methods: ['POST'])]
    public function approuver(Request $request, DetailHeuresRepository $detailHeuresRepo, StatutRepository $statutRepo): Response
    {
        try {
            // Récupérer les données JSON envoyées dans la requête POST
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['token'];

            // Vérifie si le jeton CSRF est valide
            if ($this->isCsrfTokenValid('approbationToken', $token)) {
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
        } catch (\Throwable $th) {
            $message = 'Erreur lors de l\'approbation';
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
            $this->addFlash('error', $th);
        }

        return new Response($message, $code);
    }
}
