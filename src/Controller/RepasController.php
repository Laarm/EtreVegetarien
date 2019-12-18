<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Repository\RepasRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RepasFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepasController extends AbstractController
{
    /**
     * @Route("/repas", name="repas")
     */
    public function index(ArticleRepository $repo, RepasRepository $repasRepo)
    {
        $repas = $repasRepo->findAll();
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('repas/index.html.twig', [
            'articles' => $articles,
            'repas' => $repas,
        ]);
    }

    /**
     * @Route("/repas/search", name="repas_search")
     */
    public function searchRepas(EntityManagerInterface $em, Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $result = $em->getRepository(Repas::class)->createQueryBuilder('r')
                ->select('r.id', 'r.nom', 'r.image')
                ->where('r.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%')
                ->orderBy('r.nom', 'ASC')
                ->getQuery();
            $repas = $result->getResult();
            return $this->json($repas, 200);
        }
        if ($search == "") {
            $result = $em->getRepository(Repas::class)->createQueryBuilder('r')
                ->select('r.id', 'r.nom', 'r.image')
                ->orderBy('r.nom', 'ASC')
                ->getQuery();
            $repas = $result->getResult();
            return $this->json($repas, 200);
        }
        return $this->json([], 200);
    }

    /**
     * @Route("/repas/{id}", name="repas_show")
     */
    public function showRepas(Repas $repas, ArticleRepository $repo, RepasRepository $repoRepas, RepasFavorisRepository $repoRepasFavoris, Request $request)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $autresrepas = $repoRepas->findBy(array('postedBy' => $repas->getPostedBy()), null, "8", null);
        $repasFavoris = $repoRepasFavoris->findBy(array('Repas' => $repas), null, "100", null);
        return $this->render('repas/repas.html.twig', [
            'repas' => $repas,
            'articles' => $articles,
            'autresrepas' => $autresrepas,
            'repasFavoris' => $repasFavoris,
        ]);
    }
}
