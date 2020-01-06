<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     * @param ArticleRepository $repo
     * @return Response
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('inscription.html.twig', [
            'articles' => $articles,
        ]);
    }
    public function inscriptionAction(ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, Request $request): Response
    {
        if ($this->isCsrfTokenValid('create-user-inscription', $request->get('csrfData')) && strlen($request->get('password')) > 6) {
                $sqlUser = new User();
                $sqlUser->setUsername($request->get('username'))->setPassword($passwordEncoder->encodePassword($sqlUser, $request->get('password')))->setEmail($request->get('email'))->setCreatedAt(new \DateTime());
                if (count($validator->validate($sqlUser)) == 0) {
                    $this->getDoctrine()->getRepository(User::class)->createUser($sqlUser);
                    return $this->json(['message' => 'Vous êtes bien inscrit !'], 200);
                }
                return $this->json(['message' => $validator->validate($sqlUser)], 400);
        }
        return $this->json(['message' => ['detail' => 'Votre mot de passe est trop court !']], 400);
    }
}
