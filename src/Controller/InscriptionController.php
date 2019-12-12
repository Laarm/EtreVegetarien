<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
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
            'user_first_name' => "test",
            'articles' => $articles,
        ]);
    }
    public function inscriptionAction(ValidatorInterface $validator, EntityManagerInterface $entityManager, UserRepository $repo, UserPasswordEncoderInterface $passwordEncoder, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('inscription', $submittedToken)) {
                if (
                    strlen($request->request->get('username')) > 5
                    &&
                    strlen($request->request->get('username')) < 20
                    &&
                    strlen($request->request->get('email')) > 5
                    &&
                    strlen($request->request->get('email')) < 70
                    &&
                    preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->request->get('email'))
                    &&
                    strlen($request->request->get('password')) > 6
                    &&
                    strlen($request->request->get('password')) < 70
                ) {
                    $username = htmlspecialchars($request->request->get('username'));
                    $email = htmlspecialchars($request->request->get('email'));
                    $user = $repo->findOneBy([
                        'email' => $email,
                    ]);
                    if ($user == null) {
                        $sqlUser = new User();
                        $password = $passwordEncoder->encodePassword($sqlUser, $request->request->get('password'));
                        $sqlUser->setUsername($username)
                            ->setPassword($password)
                            ->setEmail($email)
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlUser);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlUser);
                        if (count($errors) == 0) {
                            $_SESSION['email'] = $email;
                            $_SESSION['password'] = $password;
                            return new Response('Success,Vous êtes bien inscrit !,' . $sqlUser->getId());
                        } else {
                            return new Response('Error,Problème au niveau du serveur, veuillez contacter un administrateur !');
                        }
                    } else {
                        return new Response('Error,L\'email est déjà utilisé !');
                    }
                } else {
                    if (strlen($request->request->get('username')) > 5) {
                        return new Response('Error,Votre pseudonyme est trop court !');
                    }
                    if (strlen($request->request->get('username')) < 20) {
                        return new Response('Error,Votre pseudonyme est trop long !' . $request->request->get('username'));
                    }
                    if (strlen($request->request->get('email')) > 5) {
                        return new Response('Error,Votre email est trop court !');
                    }
                    if (strlen($request->request->get('email')) < 20) {
                        return new Response('Error,Votre email est trop long !');
                    }
                    if (preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i', $request->request->get('email'))) {
                        return new Response('Error,Votre email n\'est pas conforme (exemple : exemple@exemple.fr) !');
                    }
                    if (strlen($request->request->get('password')) > 6) {
                        return new Response('Error,Votre mot de passe est trop court !');
                    }
                    if (strlen($request->request->get('password')) < 70) {
                        return new Response('Error,Votre mot de passe est trop long !');
                    }
                    return new Response('Error,Veuillez remplir tout les champs !');
                }
            }
        }
    }
}
