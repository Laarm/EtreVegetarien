<?php

namespace App\Repository;

use App\Entity\Meal;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Meal::class);
        $this->validator = $validator;
    }
    public function createMeal($sqlMeal)
    {
        $this->_em->persist($sqlMeal);
        $this->_em->flush();
        return $sqlMeal->getId();
    }
    public function saveMeal($sqlMeal)
    {
        $this->_em->flush();
        return $sqlMeal;
    }
    public function deleteMeal($sqlMeal)
    {
        $this->_em->remove($sqlMeal);
        $this->_em->flush();
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
