<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\RepasFavoris;
use App\Entity\ProduitFavoris;
use App\Entity\RestaurantAvis;
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
        $repas = $this->getDoctrine()->getRepository(RepasFavoris::class)->getAllRepasAvisForUser($user);
        $produits = $this->getDoctrine()->getRepository(ProduitFavoris::class)->getAllProduitsAvisForUser($user);
        $restaurant = $this->getDoctrine()->getRepository(RestaurantAvis::class)->getAllRestaurantsAvisForUser($user);
        return $this->render('user/index.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'produits' => $produits,
            'repas' => $repas,
            'restaurants' => $restaurant,
        ]);
    }
}
