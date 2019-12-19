<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MagasinRepository;
use App\Repository\ArticleRepository;
use App\Entity\Magasin;
use App\Entity\ProduitSync;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MagasinController extends AbstractController
{
    /**
     * @Route("/magasin", name="magasin")
     */
    public function index(MagasinRepository $magasinrepo, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $magasin = $magasinrepo->findAll();
        return $this->render('magasin/index.html.twig', [
            'magasins' => $magasin,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/magasin/{id}", name="magasin_show")
     */
    public function show(Magasin $magasin, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $produits = $this->getDoctrine()->getRepository(ProduitSync::class)->getProduitOfMagasin($magasin);
        return $this->render('magasin/magasin.html.twig', [
            'magasin' => $magasin,
            'articles' => $articles,
            'produits' => $produits,
        ]);
    }

    /**
     * @Route("/magasins/search", name="magasin_search")
     */
    public function searchMagasin(Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $magasins = $this->getDoctrine()->getRepository(Magasin::class)->searchMagasin($search);
            return $this->json($magasins, 200);
        }
        if ($search == "") {
            $magasins = $this->getDoctrine()->getRepository(Magasin::class)->getAllMagasin();
            return $this->json($magasins, 200);
        }
        return $this->json([], 200);
    }
}
