<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MagasinRepository;
use App\Repository\ArticleRepository;

class MagasinController extends AbstractController
{
    /**
     * @Route("/magasin", name="magasin")
     */
    public function index(MagasinRepository $magasinrepo, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        $magasin = $magasinrepo->findAll();
        return $this->render('magasin/index.html.twig', [
            'controller_name' => 'MagasinController',
            'magasins' => $magasin,
            'articles' => $articles,
        ]);
    }
}
