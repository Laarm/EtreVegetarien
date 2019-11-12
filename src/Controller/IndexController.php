<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index()
    {
        return $this->render('index.html.twig', [
            'postInstagram' => json_decode(file_get_contents("https://api.instagram.com/v1/users/self/media/recent/?access_token=5754011249.aed5496.f04a76880baa4a93941645c151dcf531"), true),
        ]);
    }
}