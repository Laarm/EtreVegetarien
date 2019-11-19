<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DevenirVegetarienController extends AbstractController
{
    /**
     * @Route("/devenir/vegetarien", name="devenir_vegetarien")
     */
    public function index()
    {
        return $this->render('devenir_vegetarien/index.html.twig', [
            'controller_name' => 'DevenirVegetarienController',
        ]);
    }
}
