<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductFavorites;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProductFavorites|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductFavorites|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductFavorites[]    findAll()
 * @method ProductFavorites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductFavoritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, ProductFavorites::class);
        $this->validator = $validator;
        $this->security = $security;
    }
    public function getAllProductsFeedbackForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedById = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function addProductFavorites($productId)
    {
        $user = $this->security->getUser();
        $product = $this->_em
            ->getRepository(Product::class)
            ->find($productId);
        $sqlProductFavorites = new ProductFavorites();
        $sqlProductFavorites->setProductId($product)
            ->setPostedById($user)
            ->setCreatedAt(new \DateTime());
        $this->_em->persist($sqlProductFavorites);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlProductFavorites);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function removeProductFavorites($productId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedById = ' . $userId)
            ->andwhere('r.productId = ' . $productId)
            ->getQuery();
        $productFavoritesId = $result->getResult();
        $deleteFavorites = $this->find($productFavoritesId[0]['id']);
        $this->_em->remove($deleteFavorites);
        $this->_em->flush();
        $errors = $this->validator->validate($deleteFavorites);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
