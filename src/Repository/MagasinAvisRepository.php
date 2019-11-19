<?php

namespace App\Repository;

use App\Entity\MagasinAvis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MagasinAvis|null find($id, $lockMode = null, $lockVersion = null)
 * @method MagasinAvis|null findOneBy(array $criteria, array $orderBy = null)
 * @method MagasinAvis[]    findAll()
 * @method MagasinAvis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinAvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MagasinAvis::class);
    }

    // /**
    //  * @return MagasinAvis[] Returns an array of MagasinAvis objects
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
    public function findOneBySomeField($value): ?MagasinAvis
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
