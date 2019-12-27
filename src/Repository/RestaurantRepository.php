<?php

namespace App\Repository;

use App\Entity\Restaurant;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Restaurant::class);
        $this->validator = $validator;
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
    public function deleteRestaurant($sql)
    {
        $this->_em->remove($sql);
        $this->_em->flush();
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
