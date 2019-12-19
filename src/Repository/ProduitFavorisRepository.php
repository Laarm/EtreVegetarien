<?php

namespace App\Repository;

use App\Entity\ProduitFavoris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProduitFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitFavoris[]    findAll()
 * @method ProduitFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitFavoris::class);
    }
    public function getAllProduitsAvisForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedById = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
}
