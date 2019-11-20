<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class DevenirVegetarienController extends AbstractController
{
    /**
     * @Route("/devenir/vegetarien", name="devenir_vegetarien")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        return $this->render('devenir_vegetarien/index.html.twig', [
            'controller_name' => 'DevenirVegetarienController',
            'articles' => $articles,
        ]);
    }
}
