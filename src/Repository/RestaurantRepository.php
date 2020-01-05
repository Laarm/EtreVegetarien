<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Restaurant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Restaurant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Restaurant[]    findAll()
 * @method Restaurant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Restaurant::class);
    }
    public function createRestaurant($sqlRestaurant)
    {
        $this->_em->persist($sqlRestaurant);
        $this->_em->flush();
        return $sqlRestaurant->getId();
    }
    public function saveRestaurant($sqlRestaurant)
    {
        $this->_em->flush();
        return $sqlRestaurant->getId();
    }
    public function deleteRestaurant($restaurant)
    {
        $this->_em->remove($restaurant);
        $this->_em->flush();
    }
    public function searchRestaurant($search, $limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image');
        if($search !== "") {
            $result->where('m.name LIKE :search')->setParameter('search', '%' . $search . '%');
        }
        return $result->orderBy('m.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
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
