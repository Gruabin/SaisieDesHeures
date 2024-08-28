<?php

namespace App\Repository;

use App\Entity\Site;
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
    public function __construct(ManagerRegistry $registry, public Security $security)
    {
        parent::__construct($registry, TacheSpecifique::class);
    }

    /**
     * @return array<Site>
     */
    public function findAllSite(): array
    {
        $user = $this->security->getUser();

        return $this->createQueryBuilder('t')
            ->join('t.sites', 'site')
            ->andWhere('site.id = :site')
            ->setParameter('site', substr((string) $user->getUserIdentifier(), 0, 2))
            ->getQuery()
            ->getResult();
    }
}
