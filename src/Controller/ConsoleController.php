<?php

namespace App\Controller;

use App\Entity\DetailHeures;
use App\Repository\ActiviteRepository;
use App\Repository\CentreDeChargeRepository;
use App\Repository\DetailHeuresRepository;
use App\Repository\StatutRepository;
use App\Repository\TacheRepository;
use App\Repository\TacheSpecifiqueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @property ActiviteRepository        $activiteRepository
 * @property CentreDeChargeRepository  $centreDeChargeRepository
 * @property LoggerInterface           $logger
 * @property EntityManagerInterface    $entityManager
 * @property DetailHeuresRepository    $detailHeuresRepository
 * @property Security                  $security
 * @property StatutRepository          $statutRepository
 * @property TacheRepository           $tacheRepository
 * @property TacheSpecifiqueRepository $tacheSpecifiqueRepository
 */
class ConsoleController extends AbstractController
{
        public ActiviteRepository $activiteRepository;
        public CentreDeChargeRepository $centreDeChargeRepository;
        public LoggerInterface $logger;
        public EntityManagerInterface $entityManager;
        public DetailHeuresRepository $detailHeuresRepository;
        public Security $security;
        public StatutRepository $statutRepository;
        public TacheRepository $tacheRepository;
        public TacheSpecifiqueRepository $tacheSpecifiqueRepository;
    public function __construct(
        ActiviteRepository $activiteRepository,
        CentreDeChargeRepository $centreDeChargeRepository,
        LoggerInterface $logger,
        EntityManagerInterface $entityManager,
        DetailHeuresRepository $detailHeuresRepository,
        Security $security,
        StatutRepository $statutRepository,
        TacheRepository $tacheRepository,
        TacheSpecifiqueRepository $tacheSpecifiqueRepository
    ) {
        $this->activiteRepository = $activiteRepository;
        $this->centreDeChargeRepository = $centreDeChargeRepository;
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->detailHeuresRepository = $detailHeuresRepository;
        $this->security = $security;
        $this->statutRepository = $statutRepository;
        $this->tacheRepository = $tacheRepository;
        $this->tacheSpecifiqueRepository = $tacheSpecifiqueRepository;
    }

    /**
     * Fonction pour approuver les lignes de détails des heures via une requête POST.
     * Vérifie le jeton CSRF, met à jour le statut des détails conformes à approuvé, et enregistre les modifications.
     * Retourne des messages de succès ou d'erreur en fonction du résultat.
     */
    #[Route('/api/post/approuverLigne', name: 'approuverLigne', methods: ['POST'])]
    public function approuverLigne(Request $request): Response
    {
        try {
            // Récupérer les données JSON envoyées dans la requête POST
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['token'];
            // Vérifie si le jeton CSRF est valide
            if (!empty($token) && $this->isCsrfTokenValid('approbationToken', $token)) {
                $statutApprouve = $this->statutRepository->getStatutApprouve();
                $statutConforme = $this->statutRepository->getStatutConforme();
                // Parcourir les ID des détails envoyés
                foreach ($data['id'] as $ligne) {
                    $unDetail = $this->detailHeuresRepository->findOneBy(['id' => $ligne]);
                    // Vérifie si le statut du détail est conforme
                    if ($unDetail->getStatut() == $statutConforme) {
                        $unDetail->setStatut($statutApprouve);
                        $this->entityManager->persist($unDetail);
                        $this->entityManager->flush();
                        $this->logger->info('Détail n°'.$ligne.' approuvé par '.$this->getUser()->getNomEmploye());
                    }
                }
                $message = 'Saisies approuvées';
                $code = Response::HTTP_OK;
                $this->addFlash('success', $message);
            } else {
                $message = 'Jeton CSRF invalide';
                $code = Response::HTTP_UNAUTHORIZED;
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
    public function supprimerligne(Request $request): Response
    {
        try {
            // Récupérer les données JSON envoyées dans la requête POST de manière sécurisée
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
            $token = $data['token'] ?? '';

            // Vérifie si le jeton CSRF est valide
            if (!empty($token) && $this->isCsrfTokenValid('ligneToken_'.$data['id'], $token)) {
                $statutSupprime = $this->statutRepository->getStatutSupprime();
                $statutConforme = $this->statutRepository->getStatutConforme();
                $statutAnomalie = $this->statutRepository->getStatutAnomalie();
                $unDetail = $this->detailHeuresRepository->find($data['id']);

                if ($unDetail && in_array($unDetail->getStatut(), [$statutConforme, $statutAnomalie])) {
                    $unDetail->setStatut($statutSupprime);
                    $unDetail->setMotifErreur(null);
                    $this->entityManager->persist($unDetail);
                    $this->entityManager->flush();
                    $this->logger->info('Détail n°'.$data['id'].' supprimé par '.$this->getUser()->getNomEmploye());

                    $message = 'Saisie Supprimé';
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

    /**
     * Fonction pour modifier les lignes de détails des heures via une requête POST.
     * Vérifie le jeton CSRF, met à jour le statut des détails conformes à approuvé, et enregistre les modifications.
     * Retourne des messages de succès ou d'erreur en fonction du résultat.
     */
    #[Route('/api/post/modifierLigne', name: 'modifierLigne', methods: ['POST'])]
    public function modifierLigne(Request $request): Response
    {
        try {
        $data = [];
        // Récupérer les données JSON envoyées dans la requête POST
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $token = $data['token'];
        // Vérifie si le jeton CSRF est valide
        if (!empty($token) && $this->isCsrfTokenValid('ligneToken_'.$data['id'], $token)) {
            $statutAnomalie = $this->statutRepository->getStatutAnomalie();
            $statutConforme = $this->statutRepository->getStatutConforme();
            $unDetail = $this->detailHeuresRepository->findOneBy(['id' => $data['id']]);
            // Vérifie si le statut du détail est conforme
            if ($unDetail && in_array($unDetail->getStatut(), [$statutConforme, $statutAnomalie])) {
                $unDetail = $this->setDetailHeures($unDetail, $data);
                $this->entityManager->persist($unDetail);
                $this->entityManager->flush();
                $this->logger->info('Détail n°'.$data['id'].' modifié par '.$this->getUser()->getNomEmploye());
            }
            $message = 'Saisie modifié';
            $code = Response::HTTP_OK;
        } else {
            $message = 'Jeton CSRF invalide';
            $code = Response::HTTP_UNAUTHORIZED;
        }
        } catch (\Throwable) {
            $message = 'Erreur lors de la modification';
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return new Response($message, $code);
    }

    /**
     * Summary of setDetailHeures
     * @param DetailHeures $unDetail
     * @param array<mixed> $data
     * @return DetailHeures|null
     */
    private function setDetailHeures(DetailHeures $unDetail, array $data)
    {
        $statutConforme = $this->statutRepository->getStatutConforme();
        $unDetail->setTempsMainOeuvre($data['temps_main_oeuvre']);
        $unDetail->setStatut($statutConforme);
        $unDetail->setMotifErreur(null);

        // Vérifier et définir les propriétés

        if (null != $data['ordre']) {
            $unDetail->setOrdre($data['ordre']);
        } else {
            $unDetail->setOrdre(null);
        }

        if (null != $data['operation']) {
            $unDetail->setOperation($data['operation']);
        } else {
            $unDetail->setOperation(null);
        }

        if (null != $data['activite']) {
            $activite = $this->activiteRepository->find($data['activite']);
            if (!$activite) {
                $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Activite manquant');

                return null;
            }
            $unDetail->setActivite($activite);
        } else {
            $unDetail->setActivite(null);
        }

        if (null != $data['tache']) {
            if (null != $this->tacheSpecifiqueRepository->find($data['tache'])) {
                $unDetail->setTacheSpecifique($this->tacheSpecifiqueRepository->find($data['tache']));
            } elseif (null != $this->tacheRepository->find($data['tache'])) {
                $unDetail->setTache($this->tacheRepository->find($data['tache']));
            } else {
                $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Tache manquant');

                return null;
            }
        } else {
            $unDetail->setTache(null);
            $unDetail->setTacheSpecifique(null);
        }

        if (null != $data['centre_de_charge']) {
            $centreDeCharge = $this->centreDeChargeRepository->find($data['centre_de_charge']);
            if (!$centreDeCharge) {
                $this->logger->debug('DetailHeuresAPIController::setDetailHeures Object Centre de charge manquant');

                return null;
            }
            $unDetail->setCentreDeCharge($centreDeCharge);
        } else {
            $unDetail->setCentreDeCharge(null);
        }

        return $unDetail;
    }
}
