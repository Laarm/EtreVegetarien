<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MotDePasseOublieController extends AbstractController
{
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('mot_de_passe_oublie/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function PasswordForgotAjax(Request $request, \Swift_Mailer $mailer, ValidatorInterface $validator)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('password-forgot', $submittedToken)) {
                $dateTime = new \DateTime("now");
                $dateTime->modify('+15 minutes');
                $code = mt_rand(5000, 15000);
                $sqlUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("email" => $request->get('email')));
                $sqlUser->setPasswordForgot($code)
                    ->setPasswordForgotExpiration($dateTime);
                $errors = $validator->validate($sqlUser);
                if (count($errors) == 0) {
                    $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                    $message = (new \Swift_Message('Mot de passe oublié - Être Végétarien!'))
                        ->setFrom('modarateur@yaleen.fr')
                        ->setTo($request->get('email'))
                        ->setBody(
                            $this->renderView(
                                'emails/passwordforgot.html.twig',
                                ['code' => $code]
                            ),
                            'text/html'
                        );

                    $mailer->send($message);
                    return $this->json(['message' => 'Nous avons envoyer un e-mail !'], 200);
                }
                return $this->json(['message' => 'Erreur'], 400);
            }
        }
    }

    /**
     * @Route("/motdepasseoublie/{id}", name="mot_de_passe_oublie")
     */
    public function passwordForgot(ArticleRepository $repo, Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("passwordForgot" => $request->get('id')));
        if ($user && $user->getPasswordForgotExpiration() > new \DateTime("now")) {
            $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
            return $this->render('mot_de_passe_oublie/changePassword.html.twig', [
                'articles' => $articles,
                'code' => $request->get('id'),
            ]);
        } else {
            throw new NotFoundHttpException('Ce code n\'est pas valide !');
        }
    }

    public function passwordForgotChange(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('password-forgot', $submittedToken)) {
                $sqlUser = $this->getDoctrine()->getRepository(User::class)->findOneBy(array("passwordForgot" => $request->get('code')));
                $password = $passwordEncoder->encodePassword($sqlUser, $request->get('password'));
                $sqlUser->setPasswordForgot(null)
                    ->setPassword($password)
                    ->setPasswordForgotExpiration(null);
                $errors = $validator->validate($sqlUser);
                if (count($errors) == 0) {
                    $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                    return $this->json(['message' => 'Votre mot de passe à bien été modifié !'], 200);
                }
            }
        }
    }
}
