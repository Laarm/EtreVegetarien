<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Validator\Constraints as Assert;

class ParametreController extends AbstractController
{
    /**
     * @Route("/parametre", name="parametre")
     * @param ArticleRepository $repo
     * @return Response
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('parametre/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/parametre/uploadAvatar", name="upload_avatar")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function uploadImage(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('_token');
            if ($this->isCsrfTokenValid('upload-avatar', $submittedToken)) {
                $uploadedFile = $request->files->get('file');
                $imageConstraint = new Assert\Image([
                    "maxSize" => "1m"
                ]);
                $constraintViolations = $validator->validate($uploadedFile, [$imageConstraint]);
                if ($constraintViolations->count() > 0) {
                    return $this->json(['message' => $constraintViolations], 400);
                }
                $filename = uniqid("", true) . $uploadedFile->getClientOriginalName();
                $uploadedFile->move(__DIR__ . '/../../public/img/uploads/avatars', $filename);
                $oldImage = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                if (!empty($oldImage->getAvatar()) && substr($oldImage->getAvatar(), 0, 4) !== "http" && 'img/uploads/avatars/' . $filename !== $oldImage->getAvatar()) {
                    $filesystem->remove(['symlink', "../public/" . $oldImage->getAvatar(), 'activity.log']);
                }
                $oldImage->setAvatar('img/uploads/avatars/' . $filename);
                $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($oldImage);
                return $this->json(['message' => 'Vous avez bien envoyer l\'image !', 'location' => 'img/uploads/avatars/' . $filename], 200);
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }

    /**
     * @Route("/parametre/saveProfil", name="save_profil")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveProfil(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-profil', $submittedToken)) {
                $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                $sqlUser->setUsername($request->get('username'))
                    ->setEmail($request->get('email'))
                    ->setPreference($request->get('preference'))
                    ->setPreferenceCreatedAt(new \DateTime($request->get('date')))
                    ->setBio($request->get('bio'));
                $errors = $validator->validate($sqlUser);
                if (count($errors) == 0) {
                    $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                    return $this->json(['message' => 'Votre profil à bien été sauvegarder !'], 200);
                }
                return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }

    /**
     * @Route("/parametre/savePassword", name="save_password")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function savePassword(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-password', $submittedToken)) {
                if($passwordEncoder->isPasswordValid($this->getUser(), $request->get('oldPassword')) && $request->get('password') == $request->get('verifyPassword')) {
                    $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                    $sqlUser->setPassword($passwordEncoder->encodePassword($sqlUser, $request->get('password')));
                    $errors = $validator->validate($sqlUser);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                        return $this->json(['message' => 'Votre mot de passe à bien été sauvegarder !'], 200);
                    }
                    return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
                }
                return $this->json(['message' => 'Mot de passe incorrect !'], 400);
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }

    /**
     * @Route("/parametre/saveRS", name="save_rs")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function saveRS(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-rs', $submittedToken)) {
                    $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                    $sqlUser->setFacebook($request->get('facebook'))
                        ->setInstagram($request->get('instagram'))
                        ->setYoutube($request->get('youtube'));
                    $errors = $validator->validate($sqlUser);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                        return $this->json(['message' => 'Vos réseaux sociaux ont bien été sauvegarder !'], 200);
                    }
                    return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }
}
