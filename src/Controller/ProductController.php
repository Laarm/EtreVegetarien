<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductFavorites;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\ProductFavoritesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    public function productFavorites(Security $security, ValidatorInterface $validator, ProductFavoritesRepository $repoProductFavorites, Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('product-favorites', $submittedToken)) {
                if (!empty($request->get('product_id'))) {
                    $user = $security->getUser();
                    $verif = $repoProductFavorites->findBy(array('postedById' => $user, 'productId' => $request->get('product_id')));
                    if (!$verif) {
                        $user = $security->getUser();
                        $product = $this->getDoctrine()
                            ->getRepository(Product::class)
                            ->find($request->get('product_id'));
                        $sqlProductFavorites = new ProductFavorites();
                        $sqlProductFavorites->setProductId($product)
                            ->setPostedById($user)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($sqlProductFavorites);
                        if (count($errors) == 0) {
                            $sqlProductFavorites = $this->getDoctrine()->getRepository(ProductFavorites::class)->addProductFavorites($sqlProductFavorites);
                            return $this->json(['action' => "add", 'message' => "Vous avez bien ajouter ce produit en favorites", 'id' => $request->get('product_id')], 200);
                        }
                    } else {
                        $sqlProductFavorites = $this->getDoctrine()->getRepository(ProductFavorites::class)->removeProductFavorites($request->get('product_id'), $this->getUser()->getId());
                        $errors = $validator->validate($sqlProductFavorites);
                        if (count($errors) == 0) {
                            return $this->json(['action' => "delete", 'message' => "Vous avez bien supprimer ce produit en favorites", 'id' => $request->get('product_id')], 200);
                        }
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                }
            }
            return $this->json(['message' => 'Token invalide, veuillez contacter un administrateur !'], 400);
        }
    }
}
