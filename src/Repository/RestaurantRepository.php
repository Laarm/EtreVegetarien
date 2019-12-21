<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Restaurant::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createRestaurant($name, $image, $location, $adresse, $ville, $content)
    {
        $sqlRestaurant = new Restaurant();
        $sqlRestaurant->setName($name)
            ->setImage($image)
            ->setLocation($location)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setContent($content)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlRestaurant);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return $sqlRestaurant->getId();
        } else {
            return false;
        }
    }
    public function saveRestaurant($restaurantId, $name, $image, $adresse, $ville, $content)
    {
        $sqlRestaurant = $this->find($restaurantId);
        $sqlRestaurant->setName($name)
            ->setImage($image)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setContent($content);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return $restaurantId;
        } else {
            return false;
        }
    }
    public function deleteRestaurant($restaurantId)
    {
        $sqlRestaurant = $this->find($restaurantId);
        $this->entityManager->remove($sqlRestaurant);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function searchRestaurant($search, $limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->where('m.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
    public function getAllRestaurant($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->orderBy('m.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
