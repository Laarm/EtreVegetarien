<?php

namespace App\Controller\AdminEdit;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    public function newArticle()
    {
        return $this->render('admin/edit/newArticle.html.twig');
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_edit_article")
     * @param Article $article
     * @return Response
     */
    public function editArticle(Article $article)
    {
        return $this->render('admin/edit/article.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/admin/deleteArticle", name="admin_delete_article")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteArticle(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-article', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->find($request->get('id'));
            if (count($validator->validate($sqlArticle)) == 0) {
                $filesystem->remove(['symlink', "../public/" . $sqlArticle->getImage(), 'activity.log']);
                $this->getDoctrine()->getRepository(Article::class)->deleteArticle($sqlArticle);
                return $this->json(['message' => "Vous avez bien supprimer cet article", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveArticle", name="admin_save_article")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Exception
     */
    public function saveArticle(Request $request, Filesystem $filesystem, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
            $article = new Article();
            $article->setCreatedAt(new \DateTime());
            if ($request->get('article_id') !== "new") {
                $article = $em->getRepository(Article::class)->find($request->get('article_id'));
                if ($request->get('image') !== $article->getImage()) {
                    $filesystem->remove(['symlink', "../public/" . $article->getImage(), 'activity.log']);
                }
            }
            $article->setName($request->get('name'))
                ->setContent($request->get('content'))
                ->setImage($request->get('image'));
            if (count($validator->validate($article)) == 0) {
                $article = $this->getDoctrine()->getRepository(Article::class)->saveArticle($article);
                return $this->json(['message' => "L'article à bien été mis à jour !", 'articleId' => $article->getId()], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
