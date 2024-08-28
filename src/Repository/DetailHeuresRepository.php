<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<DetailHeures>
 *
 * @method DetailHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailHeures[]    findAll()
 * @method DetailHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @property EntityManagerInterface $entityManager
 * @property EmployeRepository      $employeRepository
 * @property Security               $security
 */
class DetailHeuresRepository extends ServiceEntityRepository
{
    public ManagerRegistry $registry;

    public function __construct(
        public EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        public Security $security,
        public EmployeRepository $employeRepository
    ) {
        parent::__construct($registry, DetailHeures::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures de l'utilisateur sur la journée actuelle
     */
    public function findAllTodayUser(): array
    {
        $dateAjd = strtotime('now');
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->where('d.date >= :date')
                ->andWhere('d.employe IN (:employe)')
                ->setParameter('date', date('Y-m-d', $dateAjd))
                ->setParameter('employe', $user->getUserIdentifier())
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures sur la journée actuelle
     */
    public function findAllExport(): array
    {
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->where('d.date_export IS NULL')
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures du site de l'utilisateur sur la journée actuelle
     */
    public function findAllExportSite(): array
    {
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->join('d.employe', 'employe')
                ->where('d.date_export IS NULL')
                ->andWhere('employe.id LIKE :employe')
                ->setParameter('employe', substr((string) $user->getUserIdentifier(), 0, 2).'%')
                ->orderBy('employe.id', 'DESC')
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    /**
     * Supprime tous les enregistrements de la semaine dernière.
     */
    public function findCleanLastWeek(): void
    {
        $dateLastWeek = strtotime('-1 week');
        $items = $this->createQueryBuilder('d')
            ->where('d.date < :date')
            ->setParameter('date', date('Y-m-d', $dateLastWeek))
            ->getQuery()
            ->getResult();
        $this->removeAll($items);
    }

    /**
     * Supprime tous les éléments spécifiés.
     *
     * @param array<mixed> $items les éléments à supprimer
     */
    public function removeAll($items): void
    {
        foreach ($items as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();
    }

    /**
     * Récupère le nombre total d'heures pour l'utilisateur connecté.
     *
     * @param Employe|UserInterface|string $user l'utilisateur connecté
     *
     * @return float le nombre total d'heures
     */
    public function getNbHeures($user): float
    {
        $dateAjd = strtotime('now');
        $retour = $this->createQueryBuilder('d')
            ->select('SUM(d.temps_main_oeuvre) AS total')
            ->where('d.date >= :date')
            ->andWhere('d.employe IN (:employe)')
            ->setParameter('date', date('Y-m-d', $dateAjd))
            ->setParameter('employe', $user)
            ->getQuery()
            ->getSingleResult();

        return (float) $retour['total'];
    }

    /**
     * Récupère le nombre total d'anomalies pour les responsables selectionnés.
     * ! Pas utilisé.
     *
     * @param array<Employe> $responsables les responsables selectionnés
     *
     * @return int le nombre total d'anomalies des responsables selectionnés
     */
    public function findNbAnomalie(array $responsables): int
    {
        $qb = $this->createQueryBuilder('d');
        $qb->select('COUNT(d.id)')
            ->innerJoin('d.employe', 'employe')
            ->innerJoin('employe.centre_de_charge', 'centre_de_charge')
            ->where('d.statut =  2')
            ->andWhere('centre_de_charge.responsable IN (:responsables_id)')
            ->setParameter('responsables_id', $responsables);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Récupère Les dates qui ont des détails d'heures non validées.
     *
     * @param string $responsables les responsables selectionnés
     *
     * @return array<string>|string dates possédant des détails d'heures non validées
     */
    public function findDatesDetail($responsables): array|string
    {
        $qb = $this->createQueryBuilder('d');
        $qb->select('d.date')
            ->innerJoin('d.employe', 'employe')
            ->innerJoin('employe.centre_de_charge', 'centre_de_charge')
            ->where('d.statut IN (2, 3)')
            ->andWhere('centre_de_charge.responsable IN (:responsables_id)')
            ->setParameter('responsables_id', $responsables)
            ->orderBy('d.date', 'DESC');
        $dates = $qb->getQuery()->getResult();
        $joursUniques = [];

        // Ajout du choix "Toutes les dates"
        $joursUniques['Toutes les dates'] = -1;

        //  Ajout de la date du jour
        $joursUniques[date('d-m-Y')] = date('d-m-Y');

        // Parcourir chaque date du tableau
        foreach ($dates as $date) {
            // Obtenir la partie date au format DD-MM-YYYY
            $jour = $date['date']->format('d-m-Y');

            // Ajouter le jour au tableau des jours uniques s'il n'existe pas déjà
            if (!in_array($jour, $joursUniques)) {
                $joursUniques[$jour] = $jour;
            }
        }

        return $joursUniques;
    }
}
