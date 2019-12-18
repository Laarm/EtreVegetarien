<?php

namespace App\Repository;

use App\Entity\RestaurantAvis;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method RestaurantAvis|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantAvis|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantAvis[]    findAll()
 * @method RestaurantAvis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantAvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, RestaurantAvis::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function deleteRestaurantAvis($restaurantId)
    {
        $sqlRestaurant = $this->find($restaurantId);
        $this->entityManager->remove($sqlRestaurant);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
}
