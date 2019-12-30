<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('inscription.html.twig', [
            'articles' => $articles,
        ]);
    }
    public function inscriptionAction(ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, UserRepository $repo, Request $request): Response
    {
        if ($this->isCsrfTokenValid('create-user-inscription', $request->get('csrfData'))) {
            if (strlen($request->get('username')) > 5 && strlen($request->get('username')) < 20
                && strlen($request->get('email')) > 5 && strlen($request->get('email')) < 70
                && preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->get('email'))
                && strlen($request->get('password')) > 6 && strlen($request->get('password')) < 70) {
                if (!$repo->findOneBy(['email' => $request->get('email')])) {
                    $sqlUser = new User();
                    $sqlUser->setUsername($request->get('username'))->setPassword($passwordEncoder->encodePassword($sqlUser, $request->get('password')))->setEmail($request->get('email'))->setCreatedAt(new \DateTime());
                    if (count($validator->validate($sqlUser)) == 0) {
                        $this->getDoctrine()->getRepository(User::class)->createUser($sqlUser);
                        return $this->json(['message' => 'Vous êtes bien inscrit !'], 200);
                    }
                    return $this->json(['message' => 'Problème au niveau du serveur, veuillez contacter un administrateur !'], 400);
                }
                return $this->json(['message' => 'L\'email est déjà utilisé !'], 400);
            }
            if (strlen($request->get('username')) < 5) {$message = "Votre pseudonyme est trop court !";}
            if (strlen($request->get('username')) > 20) {$message = "Votre pseudonyme est trop long !";}
            if (strlen($request->get('email')) < 5) {$message = "Votre email est trop court !";}
            if (strlen($request->get('email')) > 70) {$message = "Votre email est trop long !";}
            if (strlen($request->get('password')) < 6) {$message = "Votre mot de passe est trop court !";}
            if (strlen($request->get('password')) > 70) {$message = "Votre mot de passe est trop long !";}
            if (!preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->get('email'))) {$message = "Votre email n'est pas conforme (exemple : exemple@exemple.fr) !";}
            if($message){return $this->json(['message' => $message], 400);}
            return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
        }
    }
}
