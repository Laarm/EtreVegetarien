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
    public function getProduitOfMagasin($magasin)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.Magasin = :magasinId')
            ->setParameter('magasinId', $magasin)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
}
