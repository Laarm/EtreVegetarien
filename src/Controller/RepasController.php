<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Entity\RepasFavoris;
use App\Repository\RepasRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\RepasFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    public function searchRepas(Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $repas = $this->getDoctrine()->getRepository(Repas::class)->searchRepas($search);
            return $this->json($repas, 200);
        }
        if ($search == "") {
            $repas = $this->getDoctrine()->getRepository(Repas::class)->getAllRepas($search);
            return $this->json($repas, 200);
        }
        return $this->json([], 200);
    }

    /**
     * @Route("/repas/{id}", name="repas_show")
     */
    public function showRepas(Repas $repas, ArticleRepository $repo, RepasRepository $repoRepas, RepasFavorisRepository $repoRepasFavoris, Security $security)
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

    /**
     * @Route("/ajax/repasFavoris", name="repas_favoris")
     */
    public function repasFavoris(Security $security, RepasFavorisRepository $repoRepasFavoris, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('repas-favoris', $submittedToken)) {
                if (!empty($request->get('repas_id'))) {
                    $user = $security->getUser();
                    $verif = $repoRepasFavoris->findBy(array('postedBy' => $user, 'Repas' => $request->get('repas_id')));
                    if (!$verif) {
                        $sqlRepasFavoris = $this->getDoctrine()->getRepository(RepasFavoris::class)->addRepasFavoris($request->get('repas_id'));
                        if ($sqlRepasFavoris == "good") {
                            return $this->json(['code' => 200, 'message' => "Vous avez bien ajouter ce repas en favoris", 'id' => $request->get('repas_id')], 200);
                        }
                    } else {
                        $sqlRepasFavoris = $this->getDoctrine()->getRepository(RepasFavoris::class)->removeRepasFavoris($request->get('repas_id'), $this->getUser()->getId());
                        if ($sqlRepasFavoris == "good") {
                            return $this->json(['code' => 201, 'message' => "Vous avez bien supprimer ce repas en favoris", 'id' => $request->get('repas_id')], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                }
            }
            return $this->json(['code' => 400, 'message' => 'Token invalide, veuillez contacter un administrateur !'], 200);
        }
    }
}
