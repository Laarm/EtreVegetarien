<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\MealFavorites;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MealFavorites|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealFavorites|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealFavorites[]    findAll()
 * @method MealFavorites[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealFavoritesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, MealFavorites::class);
        $this->validator = $validator;
        $this->security = $security;
    }
    public function addMealFavorites($sql)
    {
        $this->_em->persist($sql);
        $this->_em->flush();
    }
    public function removeMealFavorites($mealId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedBy = ' . $userId)
            ->andwhere('r.Meal = ' . $mealId)
            ->getQuery();
        $mealFavoritesId = $result->getResult();
        $deleteFavorites = $this->find($mealFavoritesId[0]['id']);
        $this->_em->remove($deleteFavorites);
        $this->_em->flush();
        return $deleteFavorites;
    }
    public function getAllMealFeedbackForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
}
