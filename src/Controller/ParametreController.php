<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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
    public function uploadImage(Request $request): Response
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
                    return $this->json(['code' => 400, 'message' => 'L\'extension n\'est pas valide !'], 200);
                } else {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($this->getUser()->getId(), $locationRenvoie);
                        if ($userSql == "good") {
                            return $this->json(['code' => 200, 'message' => 'Vous avez bien envoyer l\'image !', 'location' => $locationRenvoie], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
                    }
                }
            }
            return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
        }
    }
    /**
     * @Route("/parametre/saveProfil", name="save_profil")
     */
    public function saveProfil(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-profil', $submittedToken)) {
                $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserProfil($this->getUser()->getId(), $request->get('username'), $request->get('email'), $this->getUser()->getRole(), $request->get('bio'), $request->get('preference'), $request->get('date'));
                if ($userSql) {
                    return $this->json(['code' => 200, 'message' => 'Votre profil à bien été sauvegarder !'], 200);
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur, veuillez contacter un administrateur !'], 200);
                }
            }
            return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
        }
    }
}
