<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MagasinRepository;
use App\Repository\ArticleRepository;
use App\Entity\Magasin;
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
            'controller_name' => 'MagasinController',
            'magasins' => $magasin,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/magasins/search", name="magasin_search")
     */
    public function searchMagasin(EntityManagerInterface $em, Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $result = $em->getRepository(Magasin::class)->createQueryBuilder('m')
                ->select('m.id', 'm.nom', 'm.image')
                ->where('m.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('m.nom', 'ASC')
                ->getQuery();
            $magasins = $result->getResult();
            return $this->json($magasins, 200);
        }
        return $this->json([], 200);
    }
}
