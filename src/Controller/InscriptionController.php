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
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('create-user-inscription', $submittedToken)) {
                if (
                    strlen($request->get('username')) > 5
                    &&
                    strlen($request->get('username')) < 20
                    &&
                    strlen($request->get('email')) > 5
                    &&
                    strlen($request->get('email')) < 70
                    &&
                    preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->get('email'))
                    &&
                    strlen($request->get('password')) > 6
                    &&
                    strlen($request->get('password')) < 70
                ) {
                    $username = htmlspecialchars($request->get('username'));
                    $email = htmlspecialchars($request->get('email'));
                    $user = $repo->findOneBy([
                        'email' => $email,
                    ]);
                    if ($user == null) {
                        $sqlUser = new User();
                        $password = $passwordEncoder->encodePassword($sqlUser, $request->get('password'));
                        $sqlUser->setUsername($username)
                            ->setPassword($password)
                            ->setEmail($email)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($sqlUser);
                        if (count($errors) == 0) {
                            $sqlUser = $this->getDoctrine()->getRepository(User::class)->createUser($sqlUser);
                            return $this->json(['message' => 'Vous êtes bien inscrit !'], 200);
                        }
                        return $this->json(['message' => 'Problème au niveau du serveur, veuillez contacter un administrateur !'], 400);
                    } else {
                        return $this->json(['message' => 'L\'email est déjà utilisé !'], 400);
                    }
                } else {
                    if (strlen($request->get('username')) < 5) {
                        $message = "Votre pseudonyme est trop court !";
                    }
                    if (strlen($request->get('username')) > 20) {
                        $message = "Votre pseudonyme est trop long !";
                    }
                    if (strlen($request->get('email')) < 5) {
                        $message = "Votre email est trop court !";
                    }
                    if (strlen($request->get('email')) > 70) {
                        $message = "Votre email est trop long !";
                    }
                    if (strlen($request->get('password')) < 6) {
                        $message = "Votre mot de passe est trop court !";
                    }
                    if (strlen($request->get('password')) > 70) {
                        $message = "Votre mot de passe est trop long !";
                    }
                    if (!preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->get('email'))) {
                        $message = "Votre email n'est pas conforme (exemple : exemple@exemple.fr) !";
                    }
                    if($message){return $this->json(['message' => $message], 400);}
                    return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
                }
            }
        }
    }
}
