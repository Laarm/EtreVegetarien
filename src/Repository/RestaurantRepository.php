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
    public function createRestaurant($nom, $image, $location, $adresse, $ville, $contenu)
    {
        $sqlRestaurant = new Restaurant();
        $sqlRestaurant->setNom($nom)
            ->setImage($image)
            ->setLocation($location)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setContenu($contenu)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlRestaurant);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return $sqlRestaurant->getId();
        } else {
            return "not good";
        }
    }
    public function saveRestaurant($restaurantId, $nom, $image, $adresse, $ville, $contenu)
    {
        $sqlRestaurant = $this->find($restaurantId);
        $sqlRestaurant->setNom($nom)
            ->setImage($image)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setContenu($contenu);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return $restaurantId;
        } else {
            return "not good";
        }
    }
    public function deleteRestaurant($restaurantId)
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
    public function searchRestaurant($search, $limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->where('m.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
    public function getAllRestaurant($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
