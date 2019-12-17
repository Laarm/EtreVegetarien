<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\RepasFavoris;
use App\Entity\ProduitFavoris;
use App\Entity\RestaurantAvis;
use App\Repository\ArticleRepository;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(int $id, Security $security, UserRepository $repo, ArticleRepository $articlerepo): Response
    {
        $articles = $articlerepo->findBy(array(), array('id' => 'DESC'), "4", null);
        $user = $security->getUser();
        $result = $this->getDoctrine()
            ->getRepository(RepasFavoris::class)->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        $repas = $result->getResult();
        $result = $this->getDoctrine()
            ->getRepository(ProduitFavoris::class)->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedById = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        $produit = $result->getResult();
        $result = $this->getDoctrine()
            ->getRepository(RestaurantAvis::class)->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.note', 'DESC')
            ->getQuery();
        $restaurant = $result->getResult();
        $user = $repo->find($id);
        return $this->render('user/index.html.twig', [
            'articles' => $articles,
            'user' => $user,
            'produits' => $produit,
            'repas' => $repas,
            'restaurants' => $restaurant,
        ]);
    }
}
