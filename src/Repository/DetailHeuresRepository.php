<?php

namespace App\Repository;

use App\Entity\DetailHeures;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<DetailHeures>
 *
 * @method DetailHeures|null find($id, $lockMode = null, $lockVersion = null)
 * @method DetailHeures|null findOneBy(array $criteria, array $orderBy = null)
 * @method DetailHeures[]    findAll()


 *  * @property Security        $security * @method DetailHeures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DetailHeuresRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        Security $security
        )
    {
        parent::__construct($registry, DetailHeures::class);
        $this->security = $security;
    }

       /**
        * @return DetailHeures[] retourne tout les detailheures sur la journée actuelle
        */
       public function findAllToday(): array
       {
           $dateHier = strtotime('-1 days');
           $user = $this->security->getUser();
           if(!empty($user)) {
            return $this->createQueryBuilder('d')
                    ->where('d.date > :date')
                    ->andWhere('d.employe IN (:employe)')
                    ->setParameter('date', date('Y-m-d', $dateHier))
                    ->setParameter('employe', $user->getId())
                    ->orderBy('d.date', 'ASC')
                    ->getQuery()
                    ->getResult()
            ;
           }
           return [];
       }
}
