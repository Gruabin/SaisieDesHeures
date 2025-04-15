<?php

namespace App\Repository;

use App\Entity\Statut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Statut>
 *
 * @method Statut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statut|null findOneBy(array<mixed> $criteria, array<mixed> $orderBy = null)
 * @method Statut[]    findAll()
 * @method Statut[]    findBy(array<mixed> $criteria, array<mixed> $orderBy = null, $limit = null, $offset = null)
 */
class StatutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Statut::class);
    }

    /**
     * Récupère le statut Enregistré.
     */
    public function getStatutEnregistre(): ?Statut
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', 1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère le statut Anomalie.
     */
    public function getStatutAnomalie(): ?Statut
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', 2)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère le statut Conforme.
     */
    public function getStatutConforme(): ?Statut
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', 3)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère le statut Approuvé.
     */
    public function getStatutApprouve(): ?Statut
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', 4)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère le statut Supprimé.
     */
    public function getStatutSupprime(): ?Statut
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :val')
            ->setParameter('val', 6)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
