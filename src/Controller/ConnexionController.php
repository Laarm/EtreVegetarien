<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;

class ConnexionController extends AbstractController
{
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), null, "5", null);
        return $this->render('connexion.html.twig', [
            'user_first_name' => "test",
            'articles' => $articles,
        ]);
    }

    public function logout() {
        
    }
}