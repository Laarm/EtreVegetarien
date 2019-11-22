<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionArticles(ArticleRepository $repo)
    {
        if(isset($_GET['view'])){
            $view = $_GET['view'];
            $maxView = $_GET['maxView'];
        }else{
            $view = null;
            $maxView = 100;
        }
        $articles = $repo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionArticles.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionArticlesCommentaires(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/gestionArticlesCommentaires.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionMagasins(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/gestionMagasins.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionMagasinsAvis(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/gestionMagasinsAvis.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionRestaurants(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/gestionRestaurants.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function gestionRestaurantsAvis(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/gestionRestaurantsAvis.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function produits(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/produits.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function utilisateurs(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/utilisateurs.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function repas(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/repas.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
    public function contacts(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/contacts.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }
}
