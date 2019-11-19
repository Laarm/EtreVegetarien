<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(int $id, EntityManagerInterface $entityManager, UserRepository $repo, ProduitRepository $produitrepo): Response
    {
        $user = $repo->find($id);
        $produit = $produitrepo->findAll();
        return $this->render('user/index.html.twig', [
            'user' => $user,
            'produits' => $produit,
        ]);
    }
}