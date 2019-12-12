<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Contact;
use App\Repository\ArticleRepository;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/ajax/contact/sendMessage", name="contactSendMessage")
     */
    public function sendMessage(): Response
    {
        return new Response('Error,Veuillez remplir tout les champs !');
    }
}
