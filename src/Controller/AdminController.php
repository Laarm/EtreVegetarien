<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use App\Repository\ArticleCommentaireRepository;
use App\Repository\MagasinRepository;
use App\Repository\MagasinAvisRepository;
use App\Repository\RestaurantRepository;
use App\Repository\RestaurantAvisRepository;
use App\Repository\RepasRepository;
use App\Repository\ProduitRepository;
use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);

        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/admin/GestionArticles", name="admin_gestion_article")
     */
    public function gestionArticles(ArticleRepository $repo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $articles = $repo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionArticles.html.twig', [
            'articles' => $articles,
        ]);
    }
    public function gestionArticlesCommentaires(ArticleCommentaireRepository $articleCommentaireRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $articleCommentaire = $articleCommentaireRepo->findBy(array(), null, $maxView, $view);
        return $this->render('admin/gestionArticlesCommentaires.html.twig', [
            'article_commentaire' => $articleCommentaire,
        ]);
    }
    public function gestionMagasins(MagasinRepository $gestionMagasinsRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $magasins = $gestionMagasinsRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionMagasins.html.twig', [
            'magasins' => $magasins,
        ]);
    }
    public function gestionMagasinsAvis(MagasinAvisRepository $gestionMagasinsAvisRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $magasinsAvis = $gestionMagasinsAvisRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionMagasinsAvis.html.twig', [
            'magasins_avis' => $magasinsAvis,
        ]);
    }
    public function gestionRestaurants(RestaurantRepository $gestionRestaurantRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $restaurants = $gestionRestaurantRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionRestaurants.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }
    public function gestionRestaurantsAvis(RestaurantAvisRepository $gestionRestaurantAvisRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $magasinsAvis = $gestionRestaurantAvisRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionRestaurantsAvis.html.twig', [
            'restaurants_avis' => $magasinsAvis,
        ]);
    }
    public function produits(ProduitRepository $gestionProduitRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $produits = $gestionProduitRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/produits.html.twig', [
            'produits' => $produits,
        ]);
    }
    public function utilisateurs(UserRepository $gestionUserRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $utilisateurs = $gestionUserRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/utilisateurs.html.twig', [
            'utilisateurs' => $utilisateurs,
        ]);
    }
    public function repas(RepasRepository $gestionRepasRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $repas = $gestionRepasRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/repas.html.twig', [
            'repas' => $repas,
        ]);
    }
    public function contacts(ContactRepository $gestioncontactRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $contacts = $gestioncontactRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/contacts.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
