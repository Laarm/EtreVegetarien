<?php namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\MealRepository;
use App\Repository\ArticleRepository;
use App\Repository\ContactRepository;
use App\Repository\StoreRepository;
use App\Repository\ProductRepository;
use App\Repository\RestaurantRepository;
use App\Repository\ProductSyncRepository;
use App\Repository\RestaurantFeedbackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController {
    public function index(ArticleRepository $repo) {
        $articles=$repo->findBy(array(), null, "5", null);

        return $this->render('admin/index.html.twig', [ 'articles'=> $articles,
            ]);
    }
}