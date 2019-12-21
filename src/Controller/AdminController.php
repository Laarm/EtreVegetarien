<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\MealRepository;
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
     * @Route("/admin/ManagementArticles", name="admin_management_article")
     */
    public function managementArticles(ArticleRepository $repo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $articles = $repo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/managementArticles.html.twig', [
            'articles' => $articles,
        ]);
    }
    public function managementArticlesCommentaires(ArticleCommentaireRepository $articleCommentaireRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $articleCommentaire = $articleCommentaireRepo->findBy(array(), null, $maxView, $view);
        return $this->render('admin/managementArticlesCommentaires.html.twig', [
            'article_commentaire' => $articleCommentaire,
        ]);
    }
    public function managementStores(StoreRepository $managementStoresRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $stores = $managementStoresRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/managementStores.html.twig', [
            'stores' => $stores,
        ]);
    }
    public function managementProductsStores(ProductSyncRepository $managementProductsStoresRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $products = $managementProductsStoresRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/managementProductsStores.html.twig', [
            'products' => $products,
        ]);
    }
    public function managementRestaurants(RestaurantRepository $managementRestaurantRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $restaurants = $managementRestaurantRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/managementRestaurants.html.twig', [
            'restaurants' => $restaurants,
        ]);
    }
    public function managementRestaurantsAvis(RestaurantAvisRepository $managementRestaurantAvisRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $restaurantsAvis = $managementRestaurantAvisRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/managementRestaurantsAvis.html.twig', [
            'restaurants_avis' => $restaurantsAvis,
        ]);
    }
    public function products(ProductRepository $managementProductRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $products = $managementProductRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/products.html.twig', [
            'products' => $products,
        ]);
    }
    public function users(UserRepository $managementUserRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $users = $managementUserRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }
    public function meal(MealRepository $managementMealRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $meal = $managementMealRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/meal.html.twig', [
            'meal' => $meal,
        ]);
    }
    public function contacts(ContactRepository $managementcontactRepo, Request $request)
    {
        if ($request->get('view') !== null) {
            $view = $request->get('view');
            $maxView = $request->get('maxView');
        } else {
            $view = null;
            $maxView = 100;
        }
        $contacts = $managementcontactRepo->findBy(array(), null, $maxView, $view);

        return $this->render('admin/contacts.html.twig', [
            'contacts' => $contacts,
        ]);
    }
}
