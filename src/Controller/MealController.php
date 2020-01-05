<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\MealFavorites;
use App\Repository\MealRepository;
use App\Repository\ArticleRepository;
use App\Repository\MealFavoritesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MealController extends AbstractController
{
    /**
     * @Route("/meal", name="meal")
     */
    public function index(ArticleRepository $repo, MealRepository $mealRepo)
    {
        $meal = $mealRepo->findAll();
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('meal/index.html.twig', [
            'articles' => $articles,
            'meal' => $meal,
        ]);
    }

    /**
     * @Route("/meal/search", name="meal_search")
     */
    public function searchMeal(Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $meal = $this->getDoctrine()->getRepository(Meal::class)->searchMeal($search);
            return $this->json($meal, 200);
        }
        if ($search == "") {
            $meal = $this->getDoctrine()->getRepository(Meal::class)->getAllMeal($search);
            return $this->json($meal, 200);
        }
        return $this->json([], 200);
    }

    /**
     * @Route("/meal/{id}", name="meal_show")
     */
    public function showMeal(Meal $meal, ArticleRepository $repo, MealRepository $repoMeal, MealFavoritesRepository $repoMealFavorites)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $autresmeal = $repoMeal->findBy(array('postedBy' => $meal->getPostedBy()), null, "8", null);
        $mealFavorites = $repoMealFavorites->findBy(array('Meal' => $meal), null, "100", null);
        return $this->render('meal/meal.html.twig', [
            'meal' => $meal,
            'articles' => $articles,
            'autresmeal' => $autresmeal,
            'mealFavorites' => $mealFavorites,
        ]);
    }

    /**
     * @Route("/ajax/mealFavorites", name="meal_favorites")
     */
    public function mealFavorites(Security $security, MealFavoritesRepository $repoMealFavorites, Request $request, ValidatorInterface $validator)
    {
        if ($this->isCsrfTokenValid('meal-favorites', $request->get('csrfData')) && !empty($request->get('meal_id'))) {
            if (!$repoMealFavorites->findBy(array('postedBy' => $security->getUser(), 'Meal' => $request->get('meal_id')))) {
                $sqlMealFavorites = new MealFavorites();
                $sqlMealFavorites->setMeal($this->getDoctrine()
                    ->getRepository(Meal::class)
                    ->find($request->get('meal_id')))
                    ->setPostedBy($security->getUser())
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($sqlMealFavorites)) == 0) {
                    $this->getDoctrine()->getRepository(MealFavorites::class)->addMealFavorites($sqlMealFavorites);
                    return $this->json(['action' => "add", 'message' => "Vous avez bien ajouter ce repas en favorites", 'id' => $request->get('meal_id')], 200);
                }
            } else {
                $sqlMealFavorites = $this->getDoctrine()->getRepository(MealFavorites::class)->removeMealFavorites($request->get('meal_id'), $this->getUser()->getId());
                if (count($validator->validate($sqlMealFavorites)) == 0) {
                    return $this->json(['action' => "delete", 'message' => "Vous avez bien supprimer ce repas en favorites", 'id' => $request->get('meal_id')], 200);
                }
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
