<?php namespace App\Controller\Admin;

use App\Repository\RestaurantFeedbackRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementRestaurantsController extends AbstractController {

    public function managementRestaurants(RestaurantRepository $managementRestaurantRepo, Request $request) {
        $restaurants=$managementRestaurantRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/managementRestaurants.html.twig', [ 'restaurants'=> $restaurants,
        ]);
    }

    public function managementRestaurantsFeedback(RestaurantFeedbackRepository $managementRestaurantFeedbackRepo, Request $request) {
        $restaurantsFeedback=$managementRestaurantFeedbackRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/managementRestaurantsFeedback.html.twig', [ 'restaurants_feedback'=> $restaurantsFeedback,
        ]);
    }
}