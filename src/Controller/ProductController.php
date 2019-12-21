<?php

namespace App\Controller;

use App\Entity\ProductFavorites;
use App\Repository\ProductFavoritesRepository;
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
     * @Route("/ajax/productFavorites", name="product_favorites")
     */
    public function productFavorites(Security $security, ProductFavoritesRepository $repoProductFavorites, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('product-favorites', $submittedToken)) {
                if (!empty($request->get('product_id'))) {
                    $user = $security->getUser();
                    $verif = $repoProductFavorites->findBy(array('postedById' => $user, 'productId' => $request->get('product_id')));
                    if (!$verif) {
                        $sqlProductFavorites = $this->getDoctrine()->getRepository(ProductFavorites::class)->addProductFavorites($request->get('product_id'));
                        if ($sqlProductFavorites) {
                            return $this->json(['action' => "add", 'message' => "Vous avez bien ajouter ce product en favorites", 'id' => $request->get('product_id')], 200);
                        }
                    } else {
                        $sqlProductFavorites = $this->getDoctrine()->getRepository(ProductFavorites::class)->removeProductFavorites($request->get('product_id'), $this->getUser()->getId());
                        if ($sqlProductFavorites) {
                            return $this->json(['action' => "delete", 'message' => "Vous avez bien supprimer ce product en favorites", 'id' => $request->get('product_id')], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                } else {
                    return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                }
            }
            return $this->json(['message' => 'Token invalide, veuillez contacter un administrateur !'], 400);
        }
    }
}
