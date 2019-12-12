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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function showRestaurant(Restaurant $restaurant, ArticleRepository $repo, RestaurantRepository $repoRestaurant)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        $autresrestaurants = $repoRestaurant->findBy(array('ville' => $restaurant->getVille()), null, "10", null);
        return $this->render('restaurant/restaurant.html.twig', [
            'restaurant' => $restaurant,
            'articles' => $articles,
            'autresrestaurants' => $autresrestaurants,
        ]);
    }

    /**
     * @Route("/restaurants/search", name="restaurant_search")
     */
    public function searchRestaurant(EntityManagerInterface $em, Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
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

    /**
     * @Route("/ajax/restaurant/sendNote", name="send_note_restaurant")
     */
    public function sendNote(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security): Response
    {
        $note = htmlspecialchars($_POST['note']);
        $restaurantId = htmlspecialchars($_POST['restaurant_id']);
        if (!empty($note) && !empty($restaurantId)) {
            if (empty($message)) {
                $message = null;
            } else {
                $message = htmlspecialchars($_POST['message']);
            }
            $user = $security->getUser();
            $sqlRestaurantAvis = new RestaurantAvis();
            $sqlRestaurantAvis->setRestaurant($restaurantId)
                ->setPostedBy($user->getId())
                ->setMessage($message)
                ->setNote($note)
                ->setCreatedAt(new \DateTime());
            $entityManager->persist($sqlRestaurantAvis);
            $entityManager->flush();
            $errors = $validator->validate($sqlRestaurantAvis);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => 'Merci d\'avoir donné votre avis !'], 200);
            }
        }
        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
    }
}
