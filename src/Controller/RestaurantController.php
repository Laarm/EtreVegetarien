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
    public function sendNote(Security $security, Request $request, ValidatorInterface $validator): Response
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
                    $verif = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->getFeedbackOfUser($this->getUser()->getId(), $request->get('restaurant_id'));
                    if (!$verif) {
                        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)
                            ->find($request->get('restaurant_id'));
                        $sqlRestaurantFeedback = new RestaurantFeedback();
                        $sqlRestaurantFeedback->setRestaurant($restaurant)
                            ->setPostedBy($user)
                            ->setMessage($message)
                            ->setNote($note)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($sqlRestaurantFeedback);
                        if (count($errors) == 0) {
                            $verif = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->addFeedback($sqlRestaurantFeedback);
                            return $this->json(['message' => 'Merci d\'avoir donné votre feedback !'], 200);
                        } else {
                            return $this->json(['message' => 'Erreur !'], 200);
                        }
                    } else {
                        return $this->json(['message' => 'Vous avez déjà donné votre feedback !'], 200);
                    }
                }
                return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
            }
        }
    }
}
