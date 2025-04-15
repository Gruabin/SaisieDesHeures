<?php

namespace App\Repository;

use App\Entity\FavoriTypeHeure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavoriTypeHeure>
 *
 * @method FavoriTypeHeure|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriTypeHeure|null findOneBy(array<mixed> $criteria, array<mixed> $orderBy = null)
 * @method FavoriTypeHeure[]    findAll()
 * @method FavoriTypeHeure[]    findBy(array<mixed> $criteria, array<mixed> $orderBy = null, $limit = null, $offset = null)
 */
class FavoriTypeHeureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriTypeHeure::class);
    }
}
