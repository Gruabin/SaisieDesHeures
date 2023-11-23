<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<DetailHeures>
 *
 * @method DetailHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailHeures[]    findAll()
 * @method DetailHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @property EntityManagerInterface $entityManager
 * @property Security               $security
 */
class DetailHeuresRepository extends ServiceEntityRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        Security $security,
    ) {
        parent::__construct($registry, DetailHeures::class);
        $this->security = $security;
        $this->entityManager = $entityManager;
    }

    /**
     * @return DetailHeures[] retourne tout les detailheures sur la journée actuelle
     */
    public function findAllToday(): array
    {
        $dateHier = strtotime('-1 days');
        $user = $this->security->getUser();
        if (!empty($user)) {
            return $this->createQueryBuilder('d')
                ->where('d.date > :date')
                ->andWhere('d.employe IN (:employe)')
                ->setParameter('date', date('Y-m-d', $dateHier))
                ->setParameter('employe', $user->getId())
                ->orderBy('d.date', 'DESC')
                ->getQuery()
                ->getResult();
        }

        return [];
    }

    public function findCleanLastWeek(): void
    {
        $dateLastWeek = strtotime('now');
        $items = $this->createQueryBuilder('d')
            ->where('d.date < :date')
            ->setParameter('date', date('Y-m-d', $dateLastWeek))
            ->getQuery()
            ->getResult();
        $this->removeAll($items);
    }

    public function removeAll($items)
    {
        foreach ($items as $item) {
            $this->entityManager->remove($item);
        }
        $this->entityManager->flush();
    }
}
