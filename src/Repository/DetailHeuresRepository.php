<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DetailHeures>
 *
 * @method DetailHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailHeures[]    findAll()
 * @method DetailHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailHeuresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailHeures::class);
    }

    //    /**
    //     * @return DetailHeures[] Returns an array of DetailHeures objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DetailHeures
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
