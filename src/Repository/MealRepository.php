<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Meal|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meal|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meal[]    findAll()
 * @method Meal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MealRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Meal::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createMeal($name, $image, $recipe, $user)
    {
        $sqlMeal = new Meal();
        $sqlMeal->setName($name)
            ->setImage($image)
            ->setRecipe($recipe)
            ->setPostedBy($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlMeal);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMeal);
        if (count($errors) == 0) {
            return $sqlMeal->getId();
        } else {
            return false;
        }
    }
    public function saveMeal($mealId, $name, $image, $recipe, $user)
    {
        $sqlMeal = $this->find($mealId);
        $sqlMeal->setName($name)
            ->setImage($image)
            ->setRecipe($recipe)
            ->setPostedBy($user);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMeal);
        if (count($errors) == 0) {
            return $mealId;
        } else {
            return false;
        }
    }
    public function deleteMeal($mealId)
    {
        $sqlMeal = $this->find($mealId);
        $this->entityManager->remove($sqlMeal);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMeal);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function searchMeal($search)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->where('m.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.name', 'ASC')
            ->getQuery();
        return $result->getResult();
    }
    public function getAllMeal()
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->orderBy('m.name', 'ASC')
            ->getQuery();
        return $result->getResult();
    }
}
