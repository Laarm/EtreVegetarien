<?php namespace App\Controller\Admin;

use App\Repository\MealRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementMealController extends AbstractController {

    public function meal(MealRepository $managementMealRepo, Request $request) {
        $meal=$managementMealRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/meal.html.twig', [ 'meal'=> $meal,
        ]);
    }
}