<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConnexionController extends AbstractController
{
    public function index()
    {
        return $this->render('connexion.html.twig', [
            'user_first_name' => "test",
        ]);
    }

    public function logout() {
        
    }
}