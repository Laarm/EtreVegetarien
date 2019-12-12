<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\RepasRepository;
use App\Repository\RestaurantRepository;
use App\Repository\ArticleRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(int $id, EntityManagerInterface $entityManager, UserRepository $repo, ProduitRepository $produitrepo, RepasRepository $repasrepo, RestaurantRepository $restaurantrepo, ArticleRepository $articlerepo): Response
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $user = $repo->find($id);
        $produit = $produitrepo->findAll();
        $repas = $repasrepo->findAll();
        $restaurant = $restaurantrepo->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'produits' => $produit,
            'repas' => $repas,
            'restaurants' => $restaurant,
            'articles' => $articles,
        ]);
    }
}
