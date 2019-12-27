<?php

namespace App\Repository;

use App\Entity\ProductSync;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProductSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSync[]    findAll()
 * @method ProductSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSyncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductSync::class);
    }
    public function getProductOfStore($store)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.Store = :storeId')
            ->setParameter('storeId', $store)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function deleteProductStore($sqlStore)
    {
        $this->_em->remove($sqlStore);
        $this->_em->flush();
    }
    public function createProductStore($productSync)
    {
        $this->_em->persist($productSync);
        $this->_em->flush();
    }
}
