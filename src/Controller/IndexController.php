<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpClient\HttpClient;

class IndexController extends AbstractController
{
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.instagram.com/v1/users/self/media/recent/?access_token=5754011249.aed5496.f04a76880baa4a93941645c151dcf531');
        return $this->render('index.html.twig', [
            'postInstagram' => json_decode($response->getContent(), true),
            'articles' => $articles,
        ]);
    }
}
