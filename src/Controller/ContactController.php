<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('contact/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/ajax/contact/sendMessage", name="contactSendMessage")
     */
    public function sendMessage(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('contact', $submittedToken) && $request->get('cgu') == true && !empty($request->get('namecomplet')) && !empty($request->get('email')) && !empty($request->get('subject')) && !empty($request->get('message'))) {
                $sql = $this->getDoctrine()->getRepository(Contact::class)->sendMessage($request->get('namecomplet'), $request->get('email'), $request->get('subject'), $request->get('message'));
                if ($sql) {
                    return $this->json(['message' => 'Merci de nous avoir contacter !'], 200);
                } else {
                    return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
                }
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }
}
