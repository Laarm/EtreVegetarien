<?php

namespace App\Repository;

use App\Entity\Store;
use App\Entity\Product;
use App\Entity\ProductSync;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, ProductSync::class);
        $this->entityManager = $entityManager;
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
    public function deleteProductStore($storeId)
    {
        $sqlStore = $this->find($storeId);
        $this->entityManager->remove($sqlStore);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlStore);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function createProductStore($storeId, $productId)
    {
        $storeId = $this->entityManager->getRepository(Store::class)->find($storeId);
        $productId = $this->entityManager->getRepository(Product::class)->find($productId);
        $sqlProduct = new ProductSync();
        $sqlProduct->setStore($storeId)
            ->setProduct($productId)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProduct);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduct);
        if (count($errors) == 0) {
            return $sqlProduct->getId();
        } else {
            return false;
        }
    }
}
