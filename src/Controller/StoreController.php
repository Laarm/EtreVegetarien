<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StoreRepository;
use App\Repository\ArticleRepository;
use App\Entity\Store;
use App\Entity\ProductSync;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class StoreController extends AbstractController
{
    /**
     * @Route("/store", name="store")
     * @param StoreRepository $storerepo
     * @param ArticleRepository $repo
     * @return Response
     */
    public function index(StoreRepository $storerepo, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $store = $storerepo->findAll();
        return $this->render('store/index.html.twig', [
            'stores' => $store,
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/store/{id}", name="store_show")
     * @param Store $store
     * @param ArticleRepository $repo
     * @return Response
     */
    public function show(Store $store, ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        $products = $this->getDoctrine()->getRepository(ProductSync::class)->getProductOfStore($store);
        return $this->render('store/store.html.twig', [
            'store' => $store,
            'articles' => $articles,
            'products' => $products,
        ]);
    }

    /**
     * @Route("/stores/search", name="store_search")
     * @param Request $request
     * @return Response
     */
    public function searchStore(Request $request): Response
    {
        $limit = $request->get('limit', 100);
        $search = $request->get('search');
        if (!empty($search)) {
            $stores = $this->getDoctrine()->getRepository(Store::class)->searchStore($search, $limit);
            return $this->json($stores, 200);
        }
        if ($search == "") {
            $stores = $this->getDoctrine()->getRepository(Store::class)->getAllStore($limit);
            return $this->json($stores, 200);
        }
        return $this->json([], 200);
    }
}
