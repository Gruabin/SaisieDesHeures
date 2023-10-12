<?php

namespace App\Repository;

use App\Entity\CentreDeCharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CentreDeCharge>
 *
 * @method CentreDeCharge|null find($id, $lockMode = null, $lockVersion = null)
 * @method CentreDeCharge|null findOneBy(array $criteria, array $orderBy = null)
 * @method CentreDeCharge[]    findAll()
 * @method CentreDeCharge[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CentreDeChargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CentreDeCharge::class);
    }

//    /**
//     * @return CentreDeCharge[] Returns an array of CentreDeCharge objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CentreDeCharge
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
