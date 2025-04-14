<?php

namespace App\Repository;

use App\Entity\Tache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tache>
 *
 * @method Tache|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tache|null findOneBy(array<mixed> $criteria, array<mixed> $orderBy = null)
 * @method Tache[]    findAll()
 * @method Tache[]    findBy(array<mixed> $criteria, array<mixed> $orderBy = null, $limit = null, $offset = null)
 */
class TacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tache::class);
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
