<?php

namespace App\Repository;

use App\Entity\TacheSpecifique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<TacheSpecifique>
 *
 * @method TacheSpecifique|null find($id, $lockMode = null, $lockVersion = null)
 * @method TacheSpecifique|null findOneBy(array $criteria, array $orderBy = null)
 * @method TacheSpecifique[]    findAll()
 * @method TacheSpecifique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TacheSpecifiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, TacheSpecifique::class);
        $this->security = $security;
    }

    //    /**
    //     * @return TacheSpecifique[] Returns an array of TacheSpecifique objects
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

    public function findAllSite(): array
    {
        $user = $this->security->getUser();

        return $this->createQueryBuilder('t')
            ->join('t.sites', 'site')
            ->andWhere('site.id = :site')
            ->setParameter('site', substr((string) $user->getId(), 0, 2))
            ->getQuery()
            ->getResult();
    }
}
