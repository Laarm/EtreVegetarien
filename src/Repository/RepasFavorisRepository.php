<?php

namespace App\Repository;

use App\Entity\RepasFavoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RepasFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepasFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepasFavoris[]    findAll()
 * @method RepasFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepasFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RepasFavoris::class);
    }

    // /**
    //  * @return RepasFavoris[] Returns an array of RepasFavoris objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RepasFavoris
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
