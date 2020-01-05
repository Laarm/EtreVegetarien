<?php namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementProductsController extends AbstractController {

    public function products(ProductRepository $managementProductRepo, Request $request) {
        $products=$managementProductRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/products.html.twig', [ 'products'=> $products,
        ]);
    }
}