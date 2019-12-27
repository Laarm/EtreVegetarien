<?php

namespace App\Repository;

use App\Entity\StoreFeedback;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StoreFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method StoreFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method StoreFeedback[]    findAll()
 * @method StoreFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StoreFeedback::class);
    }

    // /**
    //  * @return StoreFeedback[] Returns an array of StoreFeedback objects
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
    public function findOneBySomeField($value): ?StoreFeedback
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
