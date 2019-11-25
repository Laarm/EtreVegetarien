<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantRepository;
use App\Repository\ArticleRepository;
use App\Entity\Restaurant;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function index(RestaurantRepository $restaurantrepo, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        $restaurant = $restaurantrepo->findAll();
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurant,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/restaurant/{id}", name="restaurant_show")
     */
    public function showRestaurant(Restaurant $restaurant, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        return $this->render('restaurant/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/restaurants/search", name="restaurant_search")
     */
    public function searchRestaurant(EntityManagerInterface $em): Response
    {
        $search = $_GET['search'];
        if (!empty($search)) {
            $result = $em->getRepository(Restaurant::class)->createQueryBuilder('r')
                ->select('r.id', 'r.nom', 'r.image')
                ->where('r.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('r.nom', 'ASC')
                ->getQuery();
            $restaurants = $result->getResult();
            return $this->json($restaurants, 200);
        }
        return $this->json([], 200);
    }
}
