<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    public function sendMessage(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('contact', $request->get('csrfData')) && $request->get('cgu') == true && !empty($request->get('namecomplet')) && !empty($request->get('email')) && !empty($request->get('subject')) && !empty($request->get('message'))) {
            $sql = new Contact();
            $sql->setName($request->get('namecomplet'))
                ->setEmail($request->get('email'))
                ->setSubject($request->get('subject'))
                ->setMessage($request->get('message'))
                ->setCreatedAt(new \DateTime());
            if (count($validator->validate($sql)) == 0) {
                $this->getDoctrine()->getRepository(Contact::class)->sendMessage($sql);
                return $this->json(['message' => 'Merci de nous avoir contacter !'], 200);
            }
        }
        return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
    }
}
