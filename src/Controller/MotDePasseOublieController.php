<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class MotDePasseOublieController extends AbstractController
{
    /**
     * @Route("/mot/de/passe/oublie", name="mot_de_passe_oublie")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('mot_de_passe_oublie/index.html.twig', [
            'controller_name' => 'MotDePasseOublieController',
            'articles' => $articles,
        ]);
    }
}
