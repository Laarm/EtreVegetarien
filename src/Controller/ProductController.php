<?php

namespace App\Controller;

use App\Entity\ProductFavoris;
use App\Repository\ProductFavorisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    /**
     * @Route("/product", name="product")
     */
    public function index()
    {
        return $this->render('product/index.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }

    /**
     * @Route("/ajax/productFavoris", name="product_favoris")
     */
    public function productFavoris(Security $security, ProductFavorisRepository $repoProductFavoris, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('product-favoris', $submittedToken)) {
                if (!empty($request->get('product_id'))) {
                    $user = $security->getUser();
                    $verif = $repoProductFavoris->findBy(array('postedById' => $user, 'productId' => $request->get('product_id')));
                    if (!$verif) {
                        $sqlProductFavoris = $this->getDoctrine()->getRepository(ProductFavoris::class)->addProductFavoris($request->get('product_id'));
                        if ($sqlProductFavoris) {
                            return $this->json(['code' => 200, 'message' => "Vous avez bien ajouter ce product en favoris", 'id' => $request->get('product_id')], 200);
                        }
                    } else {
                        $sqlProductFavoris = $this->getDoctrine()->getRepository(ProductFavoris::class)->removeProductFavoris($request->get('product_id'), $this->getUser()->getId());
                        if ($sqlProductFavoris) {
                            return $this->json(['code' => 201, 'message' => "Vous avez bien supprimer ce product en favoris", 'id' => $request->get('product_id')], 200);
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
