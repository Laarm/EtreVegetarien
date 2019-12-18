<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use App\Entity\Article;
use App\Entity\Magasin;
use App\Entity\Restaurant;
use App\Entity\Produit;
use App\Entity\Repas;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;

class AdminEditController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/edit/index.html.twig', [
            'controller_name' => 'AdminEditController',
        ]);
    }

    public function newArticle()
    {
        return $this->render('admin/edit/newArticle.html.twig');
    }

    /**
     * @Route("/admin/article/{id}/edit", name="admin_edit_article")
     */
    public function editArticle(Article $article)
    {
        return $this->render('admin/edit/article.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/admin/deleteArticle", name="admin_delete_article")
     */
    public function deleteArticle(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->request->get('id'))) {
                    $article = $entityManager->getRepository(Article::class)->find($request->request->get('id'));
                    $entityManager->remove($article);
                    $entityManager->flush();
                    $errors = $validator->validate($article);
                    if (count($errors) == 0) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet article", 'id' => $request->request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'article...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveArticle", name="admin_save_article")
     */
    public function saveArticle(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->request->get('nom')) && !empty($request->request->get('contenu')) && !empty($request->request->get('article_id'))) {
                    if (empty($request->request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->request->get('image'));
                    }
                    if ($request->request->get('article_id') == "new") {
                        $sqlArticle = new Article();
                        $sqlArticle->setNom($request->request->get('nom'))
                            ->setContenu($request->request->get('contenu'))
                            ->setImage($image)
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlArticle);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlArticle);
                        $id = $sqlArticle->getId();
                        $success = "L'article à bien été créer !";
                    } else {
                        $article = $entityManager->getRepository(Article::class)->find($request->request->get('article_id'));
                        $article->setNom($request->request->get('nom'))
                            ->setContenu($request->request->get('contenu'))
                            ->setImage($request->request->get('image'));
                        $entityManager->flush();
                        $errors = $validator->validate($article);
                        $id = $request->request->get('article_id');
                        $success = "L'article à bien été mis à jour !";
                    }
                    if (!empty($request->request->get('nom')) && !empty($request->request->get('article_id'))) {
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => $success, 'articleId' => $id], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour de l\'article...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/magasin/{id}/edit", name="admin_edit_magasin")
     */
    public function editMagasin(Magasin $magasin)
    {
        return $this->render('admin/edit/magasin.html.twig', [
            'magasin' => $magasin,
        ]);
    }

    /**
     * @Route("/admin/magasin/new", name="admin_new_magasin")
     */
    public function newMagasin()
    {
        return $this->render('admin/edit/newMagasin.html.twig');
    }

    /**
     * @Route("/admin/deleteMagasin", name="admin_delete_magasin")
     */
    public function deleteMagasin(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->request->get('id'))) {
                    $article = $entityManager->getRepository(Magasin::class)->find($request->request->get('id'));
                    $entityManager->remove($article);
                    $entityManager->flush();
                    $errors = $validator->validate($article);
                    if (count($errors) == 0) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce magasin", 'id' => $request->request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du magasin...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveMagasin", name="admin_save_magasin")
     */
    public function saveMagasin(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->request->get('nom')) && !empty($request->request->get('ville')) && !empty($request->request->get('magasin_id'))) {
                    if (empty($request->request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->request->get('image'));
                    }
                    if ($request->request->get('magasin_id') == "new") {
                        $sqlMagasin = new Magasin();
                        $sqlMagasin->setNom($request->request->get('nom'))
                            ->setImage($image)
                            ->setLocation("null")
                            ->setAdresse($request->request->get('adresse'))
                            ->setVille($request->request->get('ville'))
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlMagasin);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlMagasin);
                        $id = $sqlMagasin->getId();
                        $success = "Le magasin à bien été créer !";
                    } else {
                        $magasin = $entityManager->getRepository(Magasin::class)->find($request->request->get('magasin_id'));
                        $magasin->setNom($request->request->get('nom'))
                            ->setImage($request->request->get('image'))
                            ->setAdresse($request->request->get('adresse'))
                            ->setVille($request->request->get('ville'));
                        $entityManager->flush();
                        $errors = $validator->validate($magasin);
                        $id = $request->request->get('magasin_id');
                        $success = "Le magasin à bien été mis à jour !";
                    }
                    if (!empty($request->request->get('nom')) && !empty($request->request->get('magasin_id'))) {
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => $success, 'magasinId' => $id], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour du magasin...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/restaurant/{id}/edit", name="admin_edit_restaurant")
     */
    public function editRestaurant(Restaurant $restaurant)
    {
        return $this->render('admin/edit/restaurant.html.twig', [
            'restaurant' => $restaurant,
        ]);
    }

    /**
     * @Route("/admin/restaurant/new", name="admin_new_restaurant")
     */
    public function newRestaurant()
    {
        return $this->render('admin/edit/newRestaurant.html.twig');
    }

    /**
     * @Route("/admin/deleteRestaurant", name="admin_delete_restaurant")
     */
    public function deleteRestaurant(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->request->get('id'))) {
                    $article = $entityManager->getRepository(Restaurant::class)->find($request->request->get('id'));
                    $entityManager->remove($article);
                    $entityManager->flush();
                    $errors = $validator->validate($article);
                    if (count($errors) == 0) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce restaurant", 'id' => $request->request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du restaurant...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveRestaurant", name="admin_save_restaurant")
     */
    public function saveRestaurant(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->request->get('nom')) && !empty($request->request->get('ville')) && !empty($request->request->get('restaurant_id')) && !empty($request->request->get('contenu'))) {
                    if (empty($request->request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->request->get('image'));
                    }
                    if ($request->request->get('restaurant_id') == "new") {
                        $sqlRestaurant = new Restaurant();
                        $sqlRestaurant->setNom($request->request->get('nom'))
                            ->setImage($image)
                            ->setLocation("null")
                            ->setAdresse($request->request->get('adresse'))
                            ->setVille($request->request->get('ville'))
                            ->setContenu($request->request->get('contenu'))
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlRestaurant);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlRestaurant);
                        $id = $sqlRestaurant->getId();
                        $success = "Le restaurant à bien été créer !";
                    } else {
                        $restaurant = $entityManager->getRepository(Restaurant::class)->find($request->request->get('restaurant_id'));
                        $restaurant->setNom($request->request->get('nom'))
                            ->setImage($request->request->get('image'))
                            ->setAdresse($request->request->get('adresse'))
                            ->setContenu($request->request->get('contenu'))
                            ->setVille($request->request->get('ville'));
                        $entityManager->flush();
                        $errors = $validator->validate($restaurant);
                        $id = $request->request->get('restaurant_id');
                        $success = "Le restaurant à bien été mis à jour !";
                    }
                    if (!empty($request->request->get('nom')) && !empty($request->request->get('restaurant_id')) && !empty($request->request->get('contenu'))) {
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => $success, 'restaurantId' => $id], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour du restaurant...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/produit/{id}/edit", name="admin_edit_produit")
     */
    public function editProduit(Produit $produit)
    {
        return $this->render('admin/edit/produit.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/admin/produit/new", name="admin_new_produit")
     */
    public function newProduit()
    {
        return $this->render('admin/edit/newProduit.html.twig');
    }

    /**
     * @Route("/admin/deleteProduit", name="admin_delete_produit")
     */
    public function deleteProduit(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->request->get('id'))) {
                    $article = $entityManager->getRepository(Produit::class)->find($request->request->get('id'));
                    $entityManager->remove($article);
                    $entityManager->flush();
                    $errors = $validator->validate($article);
                    if (count($errors) == 0) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce produit", 'id' => $request->request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du produit...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveProduit", name="admin_save_produit")
     */
    public function saveProduit(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->request->get('nom')) && !empty($request->request->get('produit_id'))) {
                    if (empty($request->request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->request->get('image'));
                    }
                    if ($request->request->get('produit_id') == "new") {
                        $sqlProduit = new Produit();
                        $sqlProduit->setNom($request->request->get('nom'))
                            ->setImage($image)
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlProduit);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlProduit);
                        $id = $sqlProduit->getId();
                        $success = "Le produit à bien été créer !";
                    } else {
                        $produit = $entityManager->getRepository(Produit::class)->find($request->request->get('produit_id'));
                        $produit->setNom($request->request->get('nom'))
                            ->setImage($request->request->get('image'));
                        $entityManager->flush();
                        $errors = $validator->validate($produit);
                        $id = $request->request->get('produit_id');
                        $success = "Le produit à bien été mis à jour !";
                    }
                    if (!empty($request->request->get('nom')) && !empty($request->request->get('produit_id'))) {
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => $success, 'produitId' => $id], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour du produit...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/repas/{id}/edit", name="admin_edit_repas")
     */
    public function editRepas(Repas $repas)
    {
        return $this->render('admin/edit/repas.html.twig', [
            'repas' => $repas,
        ]);
    }

    /**
     * @Route("/admin/repas/new", name="admin_new_repas")
     */
    public function newRepas()
    {
        return $this->render('admin/edit/newRepas.html.twig');
    }

    /**
     * @Route("/admin/deleteRepas", name="admin_delete_repas")
     */
    public function deleteRepas(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->request->get('id'))) {
                    $article = $entityManager->getRepository(Repas::class)->find($request->request->get('id'));
                    $entityManager->remove($article);
                    $entityManager->flush();
                    $errors = $validator->validate($article);
                    if (count($errors) == 0) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce repas", 'id' => $request->request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du repas...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveRepas", name="admin_save_repas")
     */
    public function saveRepas(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->request->get('nom')) && !empty($request->request->get('repas_id')) && !empty($_POST['recette'])) {
                    $user = $security->getUser();
                    if (empty($request->request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    }
                    if ($request->request->get('repas_id') == "new") {
                        $sqlRepas = new Repas();
                        $sqlRepas->setNom($request->request->get('nom'))
                            ->setImage($image)
                            ->setRecette($_POST['recette'])
                            ->setPostedBy($user)
                            ->setCreatedAt(new \DateTime());
                        $entityManager->persist($sqlRepas);
                        $entityManager->flush();
                        $errors = $validator->validate($sqlRepas);
                        $id = $sqlRepas->getId();
                        $success = "Le repas à bien été créer !";
                    } else {
                        $repas = $entityManager->getRepository(Repas::class)->find($request->request->get('repas_id'));
                        $repas->setNom($request->request->get('nom'))
                            ->setRecette($_POST['recette'])
                            ->setImage($request->request->get('image'));
                        $entityManager->flush();
                        $errors = $validator->validate($repas);
                        $id = $request->request->get('repas_id');
                        $success = "Le repas à bien été mis à jour !";
                    }
                    if (!empty($request->request->get('nom')) && !empty($request->request->get('repas_id'))) {
                        if (count($errors) == 0) {
                            return $this->json(['code' => 200, 'message' => $success, 'repasId' => $id], 200);
                        } else {
                            return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                        }
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour du repas...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/uploadImage", name="admin_upload_image")
     */
    public function uploadImage(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('_token');
            if ($this->isCsrfTokenValid('upload-image', $submittedToken)) {
                $filename = $_FILES['file']['name'];
                $location = "../public/img/uploads/" . time() . "-" . $filename;
                $locationRenvoie = "img/uploads/" . time() . "-" . $filename;
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
                        return $this->json(['code' => 200, 'message' => 'Vous avez bien envoyer l\'image !', 'location' => $locationRenvoie], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
                    }
                }
            }
            return $this->json(['code' => 400, 'message' => 'Erreur'], 200);
        }
    }
}
