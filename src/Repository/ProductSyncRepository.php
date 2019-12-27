<?php

namespace App\Repository;

use App\Entity\Store;
use App\Entity\Product;
use App\Entity\ProductSync;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProductSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductSync[]    findAll()
 * @method ProductSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductSyncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, ProductSync::class);
        $this->validator = $validator;
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
    public function createProductStore($sql)
    {
        $this->_em->persist($sql);
        $this->_em->flush();
    }
}
