<?php

namespace App\Repository;

use App\Entity\Restaurant;
use App\Entity\RestaurantFeedback;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method RestaurantFeedback|null find($id, $lockMode = null, $lockVersion = null)
 * @method RestaurantFeedback|null findOneBy(array $criteria, array $orderBy = null)
 * @method RestaurantFeedback[]    findAll()
 * @method RestaurantFeedback[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RestaurantFeedbackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, RestaurantFeedback::class);
        $this->validator = $validator;
    }
    public function deleteRestaurantFeedback($restaurantId)
    {
        $sqlRestaurant = $this->find($restaurantId);
        $this->_em->remove($sqlRestaurant);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlRestaurant);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
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
    public function addFeedback($restaurant, $user, $message, $note)
    {
        $restaurant = $this->_em->getRepository(Restaurant::class)
            ->find($restaurant);
        $sqlRestaurantFeedback = new RestaurantFeedback();
        $sqlRestaurantFeedback->setRestaurant($restaurant)
            ->setPostedBy($user)
            ->setMessage($message)
            ->setNote($note)
            ->setCreatedAt(new \DateTime());
        $this->_em->persist($sqlRestaurantFeedback);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlRestaurantFeedback);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
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
