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
            if ($this->isCsrfTokenValid('contact', $submittedToken) && $request->get('cgu') == true && !empty($request->get('nomcomplet')) && !empty($request->get('email')) && !empty($request->get('sujet')) && !empty($request->get('message'))) {
                $sql = $this->getDoctrine()->getRepository(Contact::class)->sendMessage($request->get('nomcomplet'), $request->get('email'), $request->get('sujet'), $request->get('message'));
                if ($sql) {
                    return $this->json(['code' => 200, 'message' => 'Merci de nous avoir contacter !'], 200);
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur, veuillez contacter un administrateur !'], 200);
                }
            }
            return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
        }
    }
}
