<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordForgotController extends AbstractController
{
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('password_forgot/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function PasswordForgotAjax(Request $request, \Swift_Mailer $mailer, ValidatorInterface $validator)
    {
        if ($this->isCsrfTokenValid('password-forgot', $request->get('csrfData'))) {
            $dateTime = new \DateTime("now");
            $sqlUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("email" => $request->get('email')));
            $sqlUser->setPasswordForgot(mt_rand(5000, 15000))
                ->setPasswordForgotExpiration($dateTime->modify('+15 minutes'));
            if (count($validator->validate($sqlUser)) == 0) {
                $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                $mailer->send((new \Swift_Message('Mot de passe oublié - Être Végétarien!'))
                    ->setFrom('modarateur@yaleen.fr')
                    ->setTo($request->get('email'))
                    ->setBody(
                        $this->renderView(
                            'emails/passwordForgot.html.twig',
                            ['code' => $sqlUser->getPasswordForgot()]
                        ),
                        'text/html'
                    ));
                return $this->json(['message' => 'Nous avons envoyer un e-mail !'], 200);
            }
            return $this->json(['message' => 'Erreur, veuillez contacter un administrateur'], 400);
        }
    }

    /**
     * @Route("/PasswordForgot/{id}", name="password_forgot")
     * @param ArticleRepository $repo
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function passwordForgot(ArticleRepository $repo, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("passwordForgot" => $request->get('id')));
        if ($user && $user->getPasswordForgotExpiration() > new \DateTime("now")) {
            $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
            return $this->render('password_forgot/changePassword.html.twig', [
                'articles' => $articles,
                'code' => $request->get('id'),
            ]);
        } else {
            throw new NotFoundHttpException('Ce code n\'est pas valide !');
        }
    }

    public function passwordForgotChange(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->isCsrfTokenValid('password-forgot', $request->get('csrfData'))) {
            $sqlUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("passwordForgot" => $request->get('code')));
            $password = $passwordEncoder->encodePassword($sqlUser, $request->get('password'));
            $sqlUser->setPasswordForgot(null)
                ->setPassword($password)
                ->setPasswordForgotExpiration(null);
            if (count($validator->validate($sqlUser)) == 0) {
                $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                return $this->json(['message' => 'Votre mot de passe à bien été modifié !'], 200);
            }
        }
    }
}
