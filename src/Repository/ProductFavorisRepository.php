<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductFavoris;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProductFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductFavoris[]    findAll()
 * @method ProductFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, ProductFavoris::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }
    public function getAllProductsAvisForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedById = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function addProductFavoris($productId)
    {
        $user = $this->security->getUser();
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($productId);
        $sqlProductFavoris = new ProductFavoris();
        $sqlProductFavoris->setProductId($product)
            ->setPostedById($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProductFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProductFavoris);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function removeProductFavoris($productId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedById = ' . $userId)
            ->andwhere('r.productId = ' . $productId)
            ->getQuery();
        $productFavorisId = $result->getResult();
        $deleteFavoris = $this->find($productFavorisId[0]['id']);
        $this->entityManager->remove($deleteFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($deleteFavoris);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
