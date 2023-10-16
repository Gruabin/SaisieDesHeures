<?php

namespace App\Repository;

use App\Entity\TypeHeures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TypeHeures>
 *
 * @method TypeHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeHeures[]    findAll()
 * @method TypeHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeHeuresRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeHeures::class);
    }

    //    /**
    //     * @return TypeHeures[] Returns an array of TypeHeures objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('t.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?TypeHeures
    //    {
    //        return $this->createQueryBuilder('t')
    //            ->andWhere('t.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
