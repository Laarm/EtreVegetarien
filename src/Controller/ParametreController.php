<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ParametreController extends AbstractController
{
    /**
     * @Route("/parametre", name="parametre")
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
     */
    public function uploadImage(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('_token');
            if ($this->isCsrfTokenValid('upload-avatar', $submittedToken)) {
                $filename = $_FILES['file']['name'];
                $location = "../public/img/uploads/avatars/" . time() . "-" . $filename;
                $locationRenvoie = "img/uploads/avatars/" . time() . "-" . $filename;
                $uploadOk = 1;
                $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
                $valid_extensions = array("jpg", "jpeg", "png");
                if (!in_array(strtolower($imageFileType), $valid_extensions)) {
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                    return $this->json(['message' => 'L\'extension n\'est pas valide !'], 200);
                } else {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                        $oldImage = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                        if (!empty($oldImage->getAvatar()) && substr($oldImage->getAvatar(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getAvatar()) {
                            $filesystem->remove(['symlink', "../public/" . $oldImage->getAvatar(), 'activity.log']);
                        }
                        $oldImage->setAvatar($locationRenvoie);
                        $errors = $validator->validate($oldImage);
                        if (count($errors) == 0) {
                            $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($oldImage);
                            return $this->json(['message' => 'Vous avez bien envoyer l\'image !', 'location' => $locationRenvoie], 200);
                        }
                        return $this->json(['message' => 'Veuillez contacter un administrateur'], 400);
                    } else {
                        return $this->json(['message' => 'Erreur'], 400);
                    }
                }
            }
            return $this->json(['message' => 'Erreur'], 400);
        }
    }
    /**
     * @Route("/parametre/saveProfil", name="save_profil")
     */
    public function saveProfil(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-profil', $submittedToken)) {
                $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($this->getUser()->getId());
                $sqlUser->setUsername($request->get('username'))
                    ->setEmail($request->get('email'))
                    ->setRole($request->get('role'))
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
}
