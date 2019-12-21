<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\MealFavoris;
use App\Repository\MealRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\MealFavorisRepository;
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
    public function showMeal(Meal $meal, ArticleRepository $repo, MealRepository $repoMeal, MealFavorisRepository $repoMealFavoris, Security $security)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $autresmeal = $repoMeal->findBy(array('postedBy' => $meal->getPostedBy()), null, "8", null);
        $mealFavoris = $repoMealFavoris->findBy(array('Meal' => $meal), null, "100", null);
        return $this->render('meal/meal.html.twig', [
            'meal' => $meal,
            'articles' => $articles,
            'autresmeal' => $autresmeal,
            'mealFavoris' => $mealFavoris,
        ]);
    }

    /**
     * @Route("/ajax/mealFavoris", name="meal_favoris")
     */
    public function mealFavoris(Security $security, MealFavorisRepository $repoMealFavoris, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('meal-favoris', $submittedToken)) {
                if (!empty($request->get('meal_id'))) {
                    $user = $security->getUser();
                    $verif = $repoMealFavoris->findBy(array('postedBy' => $user, 'Meal' => $request->get('meal_id')));
                    if (!$verif) {
                        $sqlMealFavoris = $this->getDoctrine()->getRepository(MealFavoris::class)->addMealFavoris($request->get('meal_id'));
                        if ($sqlMealFavoris) {
                            return $this->json(['code' => 200, 'message' => "Vous avez bien ajouter ce meal en favoris", 'id' => $request->get('meal_id')], 200);
                        }
                    } else {
                        $sqlMealFavoris = $this->getDoctrine()->getRepository(MealFavoris::class)->removeMealFavoris($request->get('meal_id'), $this->getUser()->getId());
                        if ($sqlMealFavoris) {
                            return $this->json(['code' => 201, 'message' => "Vous avez bien supprimer ce meal en favoris", 'id' => $request->get('meal_id')], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                }
            }
            return $this->json(['code' => 400, 'message' => 'Token invalide, veuillez contacter un administrateur !'], 200);
        }
    }
}
