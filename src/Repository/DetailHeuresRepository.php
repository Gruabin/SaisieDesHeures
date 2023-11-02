<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineExtensions\Query\Mysql;
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

       /**
        * @return DetailHeures[] retourne tout les detailheures sur la journée actuelle
        */
       public function findAllToday(): array
       {
           $dateHier = strtotime('-1 days');
           return $this->createQueryBuilder('d')
                ->where('d.date > :date')
                ->setParameter('date', date('Y-m-d', $dateHier))
                ->orderBy('d.date', 'ASC')
                ->getQuery()
                ->getResult()
           ;
       }

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
