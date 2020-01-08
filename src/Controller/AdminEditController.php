<?php

namespace App\Controller;

use App\Entity\Meal;
use App\Entity\User;
use App\Entity\Store;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\ProductSync;
use App\Entity\RestaurantFeedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\Validator\Constraints as Assert;

class AdminEditController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/edit/index.html.twig');
    }

    /**
     * @Route("/admin/uploadImage", name="admin_upload_image")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function uploadImage(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('upload-image', $request->get('_token'))) {
            $uploadedFile = $request->files->get('file');
            $imageConstraint = new Assert\Image(["maxSize" => "512k"]);
            $constraintViolations = $validator->validate($uploadedFile, [$imageConstraint]);
            if ($constraintViolations->count() > 0) {
                return $this->json(['message' => 'Erreur, veuillez contacter un administrateur !'], 400);
            }
            $filename = uniqid("", true) . $uploadedFile->getClientOriginalName();
            $uploadedFile->move(__DIR__ . '/../../public/img/uploads', $filename);
            return $this->json(['message' => 'Vous avez bien envoyer l\'image !', 'location' => 'img/uploads/' . $filename], 200);
        }
        return $this->json(['message' => 'Erreur'], 400);
    }
}
