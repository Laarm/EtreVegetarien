<?php

namespace App\Repository;

use App\Entity\Meal;
use App\Entity\MealFavoris;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method MealFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method MealFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method MealFavoris[]    findAll()
 * @method MealFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, MealFavoris::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }
    public function addMealFavoris($mealId)
    {
        $user = $this->security->getUser();
        $sqlMealFavoris = new MealFavoris();
        $meal = $this->entityManager
            ->getRepository(Meal::class)
            ->find($mealId);
        $sqlMealFavoris->setMeal($meal)
            ->setPostedBy($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlMealFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMealFavoris);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function removeMealFavoris($mealId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedBy = ' . $userId)
            ->andwhere('r.Meal = ' . $mealId)
            ->getQuery();
        $mealFavorisId = $result->getResult();
        $deleteFavoris = $this->find($mealFavorisId[0]['id']);
        $this->entityManager->remove($deleteFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($deleteFavoris);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function getAllMealAvisForUser($user)
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
