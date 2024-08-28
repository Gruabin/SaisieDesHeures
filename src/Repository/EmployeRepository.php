<?php

namespace App\Repository;

use App\Entity\Employe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Employe>
 *
 * @method Employe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employe[]    findAll()
 * @method Employe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employe::class);
    }

    /**
     * @param Employe $responsable
     * @return Employe[] Retourne les employées d'un responsable
     */
    public function findEmployeByResponsable($responsable): array
    {
        return $this->createQueryBuilder('e')
            ->join('e.centre_de_charge', 'centre_de_charge')
            ->andWhere('centre_de_charge.responsable = :responsable')
            ->setParameter('responsable', $responsable)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Employe> retourne tout les employés
     */
    public function findAll(): array
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|array<int> $id
     * @return Employe[] Retourne les employées avec un tableau d'ID
     */
    public function findEmploye($id): array
    {
        return $this->createQueryBuilder('e')
            ->where('e.id IN (:id)')
            ->setParameter('id', $id)
            ->orderBy('e.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toute les heures à controller des responsables selectionnés trié par employe.
     *
     * @param array<Employe> $responsables
     * @return array<Employe> Retourne les employées des responsables selectionnés
     */
    public function findHeuresControle($responsables): array
    {
        $qb = $this->createQueryBuilder('e');
        $qb->join('e.detailHeures', 'd', 'WITH', 'd.statut IN (2, 3)')
            ->innerJoin('e.centre_de_charge', 'centre_de_charge', 'WITH', 'centre_de_charge.responsable IN (:responsables_id)')
            ->setParameter('responsables_id', $responsables)
            ->orderBy('e.id', 'ASC');

        return $qb->getQuery()->getResult();
    }

    /**
     * Retourne l'employé s'il est un responsable.
     *
     * @param Employe $user
     * @return bool Retourne vrai si l'employé est un responsable
     */
    public function estResponsable($user): bool
    {
        $qb = $this->createQueryBuilder('e')
            ->innerJoin('e.centre_de_charge', 'centre_de_charge', 'WITH', 'centre_de_charge.responsable = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();

        return (null == $qb) ? false : true;
    }
}
