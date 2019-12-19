<?php

namespace App\Controller;

use App\Entity\ProduitFavoris;
use App\Repository\ProduitFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit", name="produit")
     */
    public function index()
    {
        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
        ]);
    }

    /**
     * @Route("/ajax/produitFavoris", name="produit_favoris")
     */
    public function produitFavoris(Security $security, ProduitFavorisRepository $repoProduitFavoris, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('produit-favoris', $submittedToken)) {
                if (!empty($request->get('produit_id'))) {
                    $user = $security->getUser();
                    $verif = $repoProduitFavoris->findBy(array('postedById' => $user, 'produitId' => $request->get('produit_id')));
                    if (!$verif) {
                        $sqlProduitFavoris = $this->getDoctrine()->getRepository(ProduitFavoris::class)->addProduitFavoris($request->get('produit_id'));
                        if ($sqlProduitFavoris == "good") {
                            return $this->json(['code' => 200, 'message' => "Vous avez bien ajouter ce produit en favoris", 'id' => $request->get('produit_id')], 200);
                        }
                    } else {
                        $sqlProduitFavoris = $this->getDoctrine()->getRepository(ProduitFavoris::class)->removeProduitFavoris($request->get('produit_id'), $this->getUser()->getId());
                        if ($sqlProduitFavoris == "good") {
                            return $this->json(['code' => 201, 'message' => "Vous avez bien supprimer ce produit en favoris", 'id' => $request->get('produit_id')], 200);
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
