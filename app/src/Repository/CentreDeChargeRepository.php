<?php

namespace App\Repository;

use App\Entity\CentreDeCharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

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
    public function __construct(
        ManagerRegistry $registry,
        Security $security,
    ) {
        parent::__construct($registry, CentreDeCharge::class);
        $this->security = $security;
    }

    /**
     * @return CentreDeCharge[] retourne les centres de charges du même site que l'utilisateur
     */
    public function findAllUser(): array
    {
        $user = $this->security->getUser();

        return $this->createQueryBuilder('c')
            ->andWhere('c.id LIKE :val')
            ->setParameter('val', substr((string) $user->getId(), 0, 2).'C%0')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findAll(): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
