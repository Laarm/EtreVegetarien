<?php

namespace App\Repository;

use App\Entity\ProductFavorites;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
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
    public function addProductFavorites($productFavorites)
    {
        $this->_em->persist($productFavorites);
        $this->_em->flush();
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
        return $deleteFavorites;
    }
}
