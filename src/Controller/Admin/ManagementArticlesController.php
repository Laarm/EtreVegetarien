<?php namespace App\Controller\Admin;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleCommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementArticlesController extends AbstractController {

    /**
     * @Route("/admin/ManagementArticles", name="admin_management_article")
     * @param ArticleRepository $repo
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function managementArticles(ArticleRepository $repo, Request $request) {
        $articles=$repo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));

        return $this->render('admin/managementArticles.html.twig', [ 'articles'=> $articles,
        ]);
    }

    public function managementArticlesComments(ArticleCommentRepository $articleCommentRepo, Request $request) {
        $articleComment=$articleCommentRepo->findBy(array(), null, $request->get('maxView', 100), $request->get('view', ""));
        return $this->render('admin/managementArticlesComments.html.twig', [ 'article_comment'=> $articleComment,
        ]);
    }
}