<?php

namespace App\Repository;

use App\Entity\ArticleCommentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ArticleCommentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleCommentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleCommentaire[]    findAll()
 * @method ArticleCommentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleCommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ArticleCommentaire::class);
    }

    // /**
    //  * @return ArticleCommentaire[] Returns an array of ArticleCommentaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleCommentaire
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
