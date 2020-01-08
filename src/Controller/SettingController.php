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

class SettingController extends AbstractController
{
    /**
     * @Route("/setting", name="setting")
     * @param ArticleRepository $repo
     * @return Response
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('id' => 'DESC'), "4", null);
        return $this->render('setting/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/setting/uploadAvatar", name="upload_avatar")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function uploadImage(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('upload-avatar', $request->get('_token'))) {
            $uploadedFile = $request->files->get('file');
            $imageConstraint = new Assert\Image([
                "maxSize" => "1m"
            ]);
            if ($validator->validate($uploadedFile, [$imageConstraint])->count() > 0) {
                return $this->json(['message' => $validator->validate($uploadedFile, [$imageConstraint])], 400);
            }
            $filename = uniqid("", true) . $uploadedFile->getClientOriginalName();
            $uploadedFile->move(__DIR__ . '/../../public/img/uploads/avatars', $filename);
            $oldImage = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
            deleteFile($filename, $oldImage->getAvatar());
            $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($oldImage->setAvatar('img/uploads/avatars/' . $filename));
            return $this->json(['message' => 'Vous avez bien envoyer l\'image !', 'location' => 'img/uploads/avatars/' . $filename], 200);
        }
        return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/setting/saveProfil", name="save_profil")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveProfil(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-profil', $request->get('csrfData'))) {
            $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
            $sqlUser->setUsername($request->get('username'))
                ->setEmail($request->get('email'))
                ->setPreference($request->get('preference'))
                ->setPreferenceCreatedAt(new \DateTime($request->get('date')))
                ->setBio($request->get('bio'));
            if (count($validator->validate($sqlUser)) == 0) {
                $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                return $this->json(['message' => 'Votre profil à bien été sauvegarder !'], 200);
            }
        }
        return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/setting/savePassword", name="save_password")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function savePassword(Request $request, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isCsrfTokenValid('save-password', $request->get('csrfData'))) {
            if($passwordEncoder->isPasswordValid($this->getUser(), $request->get('oldPassword')) && $request->get('password') == $request->get('verifyPassword')) {
                $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                $sqlUser->setPassword($passwordEncoder->encodePassword($sqlUser, $request->get('password')));
                if (count($validator->validate($sqlUser)) == 0) {
                    $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                    return $this->json(['message' => 'Votre mot de passe à bien été sauvegarder !'], 200);
                }
            }else{
                return $this->json(['message' => 'Mot de passe incorrect !'], 400);
            }
        }
        return $this->json(['message' => 'Erreur, veuillez contacter un administrateur'], 400);
    }

    /**
     * @Route("/setting/saveRS", name="save_rs")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function saveRS(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-rs', $request->get('csrfData'))) {
            $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
            $sqlUser->setFacebook($request->get('facebook'))
                ->setInstagram($request->get('instagram'))
                ->setYoutube($request->get('youtube'));
            if (count($validator->validate($sqlUser)) == 0) {
                $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                return $this->json(['message' => 'Vos réseaux sociaux ont bien été sauvegarder !'], 200);
            }
        }
        return $this->json(['message' => 'Erreur, veuillez contacter un administrateur'], 400);
    }
}
