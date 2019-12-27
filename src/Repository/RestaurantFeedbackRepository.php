<?php

namespace App\Repository;

use App\Entity\RestaurantFeedback;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method RestaurantFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantFeedback[]    findAll()
 * @method RestaurantFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RestaurantFeedback::class);
    }
    public function deleteRestaurantFeedback($sqlRestaurant)
    {
        $this->_em->remove($sqlRestaurant);
        $this->_em->flush();
    }
    public function getCountFeedback($restaurant)
    {
        $result = $this->createQueryBuilder('r')
            ->select('avg(r.note)', 'count(r)')
            ->where('r.restaurant = ' . $restaurant)
            ->getQuery();
        return $result->getResult();
    }
    public function getFeedbackOfUser($user, $restaurant)
    {
        return $this->findOneBy([
            'restaurant' => $restaurant,
            'postedBy' => $user,
        ]);
    }
    public function addFeedback($restaurantFeedback)
    {
        $this->_em->persist($restaurantFeedback);
        $this->_em->flush();
    }
    public function getAllRestaurantsFeedbackForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.note', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
}
