<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\RepasRepository;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use App\Repository\StoreRepository;
use App\Repository\ProductRepository;
use App\Repository\RestaurantRepository;
use App\Repository\StoreAvisRepository;
use App\Repository\ProductSyncRepository;
use App\Repository\RestaurantAvisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleCommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function gestionStores(StoreRepository $gestionStoresRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $stores = $gestionStoresRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionStores.html.twig', [
            'stores' => $stores,
        ]);
    }
    public function gestionProductsStores(ProductSyncRepository $gestionProductsStoresRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $products = $gestionProductsStoresRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionProductsStores.html.twig', [
            'products' => $products,
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
        $restaurantsAvis = $gestionRestaurantAvisRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/gestionRestaurantsAvis.html.twig', [
            'restaurants_avis' => $restaurantsAvis,
        ]);
    }
    public function products(ProductRepository $gestionProductRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $products = $gestionProductRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/products.html.twig', [
            'products' => $products,
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
