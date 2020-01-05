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
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData')) && !empty($request->get('name')) && !empty($request->get('product_id'))) {
            $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            if (!empty($request->get('image'))) {
                $image = htmlspecialchars($request->get('image'));
            }
            if ($request->get('product_id') == "new") {
                $product = new Product();
                $product->setName($request->get('name'))->setImage($image)->setCreatedAt(new \DateTime());
                if (count($validator->validate($product)) == 0) {
                    $product = $this->getDoctrine()->getRepository(Product::class)->createProduct($product);
                    return $this->json(['message' => "Le produit à bien été créer !", 'productId' => $product], 200);
                }
            } else {
                $product = $this->getDoctrine()->getRepository(Product::class)->find($request->get('product_id'));
                $product->setName($request->get('name'))->setImage($image);
                if (count($validator->validate($product)) == 0) {
                    if (substr($product->getImage(), 0, 4) !== "http" && $request->get('image') !== $product->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $product->getImage(), 'activity.log']);
                    }
                    $product = $this->getDoctrine()->getRepository(Product::class)->saveProduct($product);
                    return $this->json(['message' => "Le produit à bien été mis à jour !", 'productId' => $product->getId()], 200);
                }
            }
            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
        }
        return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
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
