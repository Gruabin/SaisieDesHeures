<?php

namespace App\Repository;

use App\Entity\Ordre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ordre>
 *
 * @method Ordre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ordre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ordre[]    findAll()
 * @method Ordre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ordre::class);
    }

    //    /**
    //     * @return Ordre[] Returns an array of Ordre objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }
    public function findAll(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
