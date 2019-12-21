<?php

namespace App\Repository;

use App\Entity\StoreAvis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StoreAvis|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreAvis|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreAvis[]    findAll()
 * @method StoreAvis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreAvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreAvis::class);
    }

    // /**
    //  * @return StoreAvis[] Returns an array of StoreAvis objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StoreAvis
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
