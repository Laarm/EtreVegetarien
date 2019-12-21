<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\MealFavorites;
use App\Entity\ProductFavorites;
use App\Entity\RestaurantFeedback;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(int $id, UserRepository $repo, ArticleRepository $articlerepo): Response
    {
        $articles = $articlerepo->findBy(array(), array('id' => 'DESC'), "4", null);
        $user = $repo->find($id);
        $meal = $this->getDoctrine()->getRepository(MealFavorites::class)->getAllMealFeedbackForUser($user);
        $products = $this->getDoctrine()->getRepository(ProductFavorites::class)->getAllProductsFeedbackForUser($user);
        $restaurant = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->getAllRestaurantsFeedbackForUser($user);
        return $this->render('user/index.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'products' => $products,
            'meal' => $meal,
            'restaurants' => $restaurant,
        ]);
    }
}
