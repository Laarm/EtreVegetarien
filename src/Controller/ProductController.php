<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductFavorites;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        return $this->render('product/index.html.twig');
    }

    /**
     * @Route("/ajax/productFavorites", name="product_favorites")
     * @param Security $security
     * @param ValidatorInterface $validator
     * @param ProductFavoritesRepository $repoProductFavorites
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function productFavorites(Security $security, ValidatorInterface $validator, ProductFavoritesRepository $repoProductFavorites, Request $request)
    {
        if ($this->isCsrfTokenValid('product-favorites', $request->get('csrfData')) && !empty($request->get('product_id'))) {
            if (!$repoProductFavorites->findBy(array('postedById' => $security->getUser(), 'productId' => $request->get('product_id')))) {
                $product = $this->getDoctrine()
                    ->getRepository(Product::class)
                    ->find($request->get('product_id'));
                $sqlProductFavorites = new ProductFavorites();
                $sqlProductFavorites->setProductId($product)
                    ->setPostedById($security->getUser())
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($sqlProductFavorites)) == 0) {
                    $this->getDoctrine()->getRepository(ProductFavorites::class)->addProductFavorites($sqlProductFavorites);
                    return $this->json(['action' => "add", 'message' => "Vous avez bien ajouter ce produit en favorites", 'id' => $request->get('product_id')], 200);
                }
            } else {
                $this->getDoctrine()->getRepository(ProductFavorites::class)->removeProductFavorites($request->get('product_id'), $this->getUser()->getId());
                return $this->json(['action' => "delete", 'message' => "Vous avez bien supprimer ce produit en favorites", 'id' => $request->get('product_id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
