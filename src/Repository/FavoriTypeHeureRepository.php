<?php

namespace App\Repository;

use App\Entity\FavoriTypeHeure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriTypeHeure>
 *
 * @method FavoriTypeHeure|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriTypeHeure|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriTypeHeure[]    findAll()
 * @method FavoriTypeHeure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriTypeHeureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriTypeHeure::class);
    }

    //    /**
    //     * @return FavoriTypeHeure[] Returns an array of FavoriTypeHeure objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?FavoriTypeHeure
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
