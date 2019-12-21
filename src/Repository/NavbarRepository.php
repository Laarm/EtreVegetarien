<?php

namespace App\Repository;

use App\Entity\Navbar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Navbar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Navbar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Navbar[]    findAll()
 * @method Navbar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavbarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Navbar::class);
    }

    // /**
    //  * @return Navbar[] Returns an array of Navbar objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Navbar
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
