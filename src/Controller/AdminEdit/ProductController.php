<?php

namespace App\Controller\AdminEdit;

use App\Entity\Product;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    /**
     * @Route("/admin/product/{id}/edit", name="admin_edit_product")
     */
    public function editProduct(Product $product)
    {
        return $this->render('admin/edit/product.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/product/new", name="admin_new_product")
     */
    public function newProduct()
    {
        return $this->render('admin/edit/newProduct.html.twig');
    }

    /**
     * @Route("/admin/deleteProduct", name="admin_delete_product")
     */
    public function deleteProduct(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-product', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $filesystem->remove(['symlink', "../public/" . $sql->getImage(), 'activity.log']);
                $this->getDoctrine()->getRepository(Product::class)->deleteProduct($sql);
                return $this->json(['message' => "Vous avez bien supprimer ce produit", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveProduct", name="admin_save_product")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveProduct(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
            $product = new Product();
            $product->setCreatedAt(new \DateTime());
            if ($request->get('product_id') !== "new") {
                $product = $this->getDoctrine()->getRepository(Product::class)->find($request->get('product_id'));
                if ($request->get('image') !== $product->getImage()) {
                    $filesystem->remove(['symlink', "../public/" . $product->getImage(), 'activity.log']);
                }
            }
            $product->setName($request->get('name'))->setImage($request->get('image'));
            if (count($validator->validate($product)) == 0) {
                $product = $this->getDoctrine()->getRepository(Product::class)->saveProduct($product);
                return $this->json(['message' => "Le produit à bien été mis à jour !", 'productId' => $product->getId()], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/products/search", name="product_search")
     * @param Request $request
     * @return Response
     */
    public function searchProduct(Request $request): Response
    {
        if (!empty($request->get('search'))) {
            $products = $this->getDoctrine()->getRepository(Product::class)->searchProduct($request->get('search'), 3);
            return $this->json($products, 200);
        }
        if ($request->get('search') == "") {
            $products = $this->getDoctrine()->getRepository(Product::class)->getAllProduct($request->get('search'), 3);
            return $this->json($products, 200);
        }
        return $this->json([], 200);
    }
}
