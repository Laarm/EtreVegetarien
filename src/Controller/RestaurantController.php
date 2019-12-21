<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantAvisRepository;
use App\Repository\ArticleRepository;
use App\Entity\Restaurant;
use App\Entity\RestaurantAvis;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurants", name="restaurants")
     */
    public function index(RestaurantRepository $restaurantrepo, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $restaurant = $restaurantrepo->findAll();
        return $this->render('restaurant/index.html.twig', [
            'restaurants' => $restaurant,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/restaurant/{id}", name="restaurant_show")
     */
    public function showRestaurant(Restaurant $restaurant, ArticleRepository $repo, RestaurantRepository $repoRestaurant, RestaurantAvisRepository $repoRestaurantAvis, Request $request)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $autresrestaurants = $repoRestaurant->findBy(array('ville' => $restaurant->getVille()), null, "10", null);
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $restaurantsSom = $this->getDoctrine()->getRepository(RestaurantAvis::class)->getCountAvis($restaurant->getId());
        $restaurantAvis = $repoRestaurantAvis->findBy(array('restaurant' => $restaurant), null, $maxView, $view);
        return $this->render('restaurant/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'articles' => $articles,
            'autresrestaurants' => $autresrestaurants,
            'restaurantAvisAll' => $restaurantAvis,
            'restaurantNote' => $restaurantsSom[0][1],
            'restaurantAvisCount' => $restaurantsSom[0][2],
        ]);
    }

    /**
     * @Route("/restaurants/search", name="restaurant_search")
     */
    public function searchRestaurant(Request $request): Response
    {
        if (!empty($request->get('limit'))) {
            $limit = $request->get('limit');
        } else {
            $limit = "100";
        }
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->searchRestaurant($search, $limit);
            return $this->json($restaurants, 200);
        }
        if ($search == "") {

            $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->getAllRestaurant($limit);
            return $this->json($restaurants, 200);
        }
        return $this->json([], 200);
    }

    /**
     * @Route("/ajax/restaurant/sendNote", name="send_note_restaurant")
     */
    public function sendNote(Security $security, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('send-note', $submittedToken)) {
                $note = $request->get('note');
                if (!empty($note) && !empty($request->get('restaurant_id'))) {
                    if ($request->get('message') == "") {
                        $message = null;
                    } else {
                        $message = $request->get('message');
                    }
                    $user = $security->getUser();
                    $verif = $this->getDoctrine()->getRepository(RestaurantAvis::class)->getAvisOfUser($this->getUser()->getId(), $request->get('restaurant_id'));
                    if (!$verif) {
                        $verif = $this->getDoctrine()->getRepository(RestaurantAvis::class)->addAvis($request->get('restaurant_id'), $user, $message, $note);
                        if ($verif) {
                            return $this->json(['code' => 200, 'message' => 'Merci d\'avoir donné votre avis !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Vous avez déjà donné votre avis !'], 200);
                    }
                }
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        }
    }
}
