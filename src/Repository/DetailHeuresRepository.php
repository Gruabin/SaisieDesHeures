<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<DetailHeures>
 *
 * @method DetailHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailHeures[]    findAll()
 * @method DetailHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @property EntityManagerInterface $entityManager
 * @property Security               $security
 */
class DetailHeuresRepository extends ServiceEntityRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        Security $security,
    ) {
        parent::__construct($registry, DetailHeures::class);
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAllSite(): array
    {
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->setParameter('employe', substr((string) $user->getId(), 0, 2).'%')
                ->orderBy('d.id', 'ASC')
                ->getQuery()
                ->getResult();
        }
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures de l'utilisateur sur la journée actuelle
     */
    public function findAllTodayUser(): array
    {
        $dateHier = strtotime('now');
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->where('d.date >= :date')
                ->andWhere('d.employe IN (:employe)')
                ->setParameter('date', date('Y-m-d', $dateHier))
                ->setParameter('employe', $user->getId())
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures sur la journée actuelle
     */
    public function findAllToday(): array
    {
        $dateHier = strtotime('now');
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->where('d.date >= :date')
                ->setParameter('date', date('Y-m-d', $dateHier))
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures du site de l'utilisateur sur la journée actuelle
     */
    public function findAllTodaySite(): array
    {
        $dateHier = strtotime('now');
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->join('d.employe', 'employe')
                ->where('d.date >= :date')
                ->andWhere('employe.id LIKE :employe')
                ->setParameter('date', date('Y-m-d', $dateHier))
                ->setParameter('employe', substr((string) $user->getId(), 0, 2).'%')
                ->orderBy('employe.id', 'DESC')
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

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

    public function removeAll($items)
    {
        foreach ($items as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();
    }

    public function getNbHeures(): array
    {
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->select('SUM(d.temps_main_oeuvre) AS total')
                ->andWhere('d.employe IN (:employe)')
                ->setParameter('employe', $user->getId())
                ->getQuery()
                ->getSingleResult();
        }
    }
}
