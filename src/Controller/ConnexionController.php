<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    public function index(ArticleRepository $repo, AuthenticationUtils $authenticationUtils)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('connexion.html.twig', [
            'articles' => $articles,
            'error'         => $error,
        ]);
    }

    public function logout()
    {
    }
}
