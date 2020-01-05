<?php namespace App\Controller\Admin;

use App\Repository\ProductSyncRepository;
use App\Repository\StoreRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementStoresController extends AbstractController {

    public function managementStores(StoreRepository $managementStoresRepo, Request $request) {
        $stores=$managementStoresRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/managementStores.html.twig', [ 'stores'=> $stores,
        ]);
    }

    public function managementProductsStores(ProductSyncRepository $managementProductsStoresRepo, Request $request) {
        $products=$managementProductsStoresRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/managementProductsStores.html.twig', [ 'products'=> $products,
        ]);
    }
}