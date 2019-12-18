<?php

namespace App\Controller;

use App\Entity\Repas;
use App\Entity\RepasFavoris;
use App\Entity\Restaurant;
use App\Entity\RestaurantAvis;
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
    public function showRepas(Repas $repas, ArticleRepository $repo, RepasRepository $repoRepas, RepasFavorisRepository $repoRepasFavoris, Security $security)
    {
        $user = $security->getUser();
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
    public function repasFavoris(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security, RepasFavorisRepository $repoRepasFavoris, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('repas-favoris', $submittedToken)) {
                if (!empty($request->get('repas_id'))) {
                    $user = $security->getUser();
                    $repas = $repoRepasFavoris->find($request->get('repas_id'));
                    $verif = $repoRepasFavoris->findBy(array('postedBy' => $user, 'Repas' => $request->get('repas_id')));
                    if (!$verif) {
                        $sqlRepasFavoris = new RepasFavoris();
                        $repas = $this->getDoctrine()
                            ->getRepository(Repas::class)
                            ->find($request->get('repas_id'));
                        $sqlRepasFavoris->setRepas($repas)
                            ->setPostedBy($user)
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlRepasFavoris);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlRepasFavoris);
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => "Vous avez bien ajouter ce repas en favoris", 'id' => $request->get('repas_id')], 200);
                        }
                    } else {
                        $result = $this->getDoctrine()->getRepository(RepasFavoris::class)->createQueryBuilder('r')
                            ->select('r.id')
                            ->where('r.postedBy = ' . $this->getUser()->getId())
                            ->andwhere('r.Repas = ' . $request->get('repas_id'))
                            ->getQuery();
                        $repasFavorisId = $result->getResult();
                        $deleteFavoris = $entityManager->getRepository(RepasFavoris::class)->find($repasFavorisId[0]['id']);
                        $entityManager->remove($deleteFavoris);
                        $entityManager->flush();
                        $errors = $validator->validate($deleteFavoris);
                        if (count($errors) == 0) {
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
