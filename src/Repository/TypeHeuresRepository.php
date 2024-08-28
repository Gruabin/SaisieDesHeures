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
}
