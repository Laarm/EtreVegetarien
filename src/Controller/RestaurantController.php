<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantFeedback;
use App\Repository\ArticleRepository;
use App\Repository\RestaurantRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\RestaurantFeedbackRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    /**
     * @Route("/restaurants", name="restaurants")
     * @param RestaurantRepository $restaurantrepo
     * @param ArticleRepository $repo
     * @return Response
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
     * @param Restaurant $restaurant
     * @param ArticleRepository $repo
     * @param RestaurantRepository $repoRestaurant
     * @param RestaurantFeedbackRepository $repoRestaurantFeedback
     * @param Request $request
     * @return Response
     */
    public function showRestaurant(Restaurant $restaurant, ArticleRepository $repo, RestaurantRepository $repoRestaurant, RestaurantFeedbackRepository $repoRestaurantFeedback, Request $request)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $autresrestaurants = $repoRestaurant->findBy(array('city' => $restaurant->getCity()), null, "10", null);
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $restaurantsSom = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->getCountFeedback($restaurant->getId());
        $restaurantFeedback = $repoRestaurantFeedback->findBy(array('restaurant' => $restaurant), null, $maxView, $view);
        return $this->render('restaurant/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'articles' => $articles,
            'autresrestaurants' => $autresrestaurants,
            'restaurantFeedbackAll' => $restaurantFeedback,
            'restaurantNote' => $restaurantsSom[0][1],
            'restaurantFeedbackCount' => $restaurantsSom[0][2],
        ]);
    }

    /**
     * @Route("/restaurants/search", name="restaurant_search")
     * @param Request $request
     * @return Response
     */
    public function searchRestaurant(Request $request): Response
    {
        $limit = $request->get('limit', 100);
        $search = $request->get('search', "");
        $restaurants = $this->getDoctrine()->getRepository(Restaurant::class)->searchRestaurant($search, $limit);
        return $this->json($restaurants, 200);
    }

    /**
     * @Route("/ajax/restaurant/sendNote", name="send_note_restaurant")
     * @param Security $security
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function sendNote(Security $security, Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('send-note', $request->get('csrfData')) && !empty($request->get('note')) && !empty($request->get('restaurant_id'))) {
            if (!$this->getDoctrine()->getRepository(RestaurantFeedback::class)->getFeedbackOfUser($this->getUser()->getId(), $request->get('restaurant_id'))) {
                $sqlRestaurantFeedback = new RestaurantFeedback();
                $sqlRestaurantFeedback->setRestaurant($this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id')))
                    ->setPostedBy($security->getUser())
                    ->setMessage($request->get('message', ""))
                    ->setNote($request->get('note'))
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($sqlRestaurantFeedback)) == 0) {
                    $this->getDoctrine()->getRepository(RestaurantFeedback::class)->addFeedback($sqlRestaurantFeedback);
                    return $this->json(['message' => 'Merci d\'avoir donné votre avis !'], 200);
                }
            } else {
                return $this->json(['message' => 'Vous avez déjà donné votre avis !'], 200);
            }
            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
        }
    }
}
