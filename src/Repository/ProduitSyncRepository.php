<?php

namespace App\Repository;

use App\Entity\ProduitSync;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProduitSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitSync[]    findAll()
 * @method ProduitSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitSyncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitSync::class);
    }

    // /**
    //  * @return ProduitSync[] Returns an array of ProduitSync objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProduitSync
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
