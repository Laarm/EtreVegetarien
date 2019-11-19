<?php

namespace App\Repository;

use App\Entity\RestaurantAvis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RestaurantAvis|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantAvis|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantAvis[]    findAll()
 * @method RestaurantAvis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantAvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantAvis::class);
    }

    // /**
    //  * @return RestaurantAvis[] Returns an array of RestaurantAvis objects
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
    public function findOneBySomeField($value): ?RestaurantAvis
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
