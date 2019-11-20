<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantRepository;
use App\Repository\ArticleRepository;

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
            'controller_name' => 'RestaurantController',
            'restaurants' => $restaurant,
            'articles' => $articles,
        ]);
    }
}
