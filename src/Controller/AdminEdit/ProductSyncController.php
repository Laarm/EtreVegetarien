<?php

namespace App\Controller\AdminEdit;

use App\Entity\Product;
use App\Entity\ProductSync;
use App\Entity\Store;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductSyncController extends AbstractController
{
    /**
     * @Route("/admin/deleteProductStore", name="admin_delete_product_store")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteProductStore(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-productsync', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(ProductSync::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $this->getDoctrine()->getRepository(ProductSync::class)->deleteProductStore($sql);
                return $this->json(['message' => "Vous avez bien supprimer ce product du store", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Erreur lors de la suppression du store...'], 400);
    }

    /**
     * @Route("/admin/addProductSync", name="admin_add_product_store")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function addProductSync(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData')) && !empty($request->get('storeId')) && !empty($request->get('productId'))) {
            $sql = new ProductSync();
            $sql->setStore($this->getDoctrine()->getRepository(Store::class)->find($request->get('storeId')))
                ->setProduct($this->getDoctrine()->getRepository(Product::class)->find($request->get('productId')))
                ->setCreatedAt(new \DateTime());
            if (count($validator->validate($sql)) == 0) {
                $this->getDoctrine()->getRepository(ProductSync::class)->createProductStore($sql);
                return $this->json(['message' => "Le produit à bien été ajouter au store !", 'productId' => $sql->getId()], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
