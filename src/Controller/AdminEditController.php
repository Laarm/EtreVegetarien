<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Repas;
use App\Entity\Article;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Restaurant;
use App\Entity\RestaurantAvis;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $article = $this->getDoctrine()->getRepository(Article::class)->deleteArticle($request->get('id'));
                    // $article = $entityManager->getRepository(Article::class)->find($request->get('id'));
                    // $entityManager->remove($article);
                    // $entityManager->flush();
                    // $errors = $validator->validate($article);
                    if ($article == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet article", 'id' => $request->get('id')], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('nom')) && !empty($request->get('contenu')) && !empty($request->get('article_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('article_id') == "new") {
                        // $sqlArticle = new Article();
                        // $sqlArticle->setNom($request->get('nom'))
                        //     ->setContenu($request->get('contenu'))
                        //     ->setImage($image)
                        //     ->setCreatedAt(new \DateTime());
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->createArticle($request->get('nom'), $request->get('contenu'), $image);
                        // $entityManager->persist($sqlArticle);
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlArticle);
                        $id = $sqlArticle;
                        $success = "L'article à bien été créer !";
                    } else {
                        // $article = $entityManager->getRepository(Article::class)->find($request->get('article_id'));
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->saveArticle($request->get('article_id'), $request->get('nom'), $request->get('contenu'), $image);
                        // $article->setNom($request->get('nom'))
                        //     ->setContenu($request->get('contenu'))
                        //     ->setImage($request->get('image'));
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlArticle);
                        // $id = $request->get('article_id');
                        $success = "L'article à bien été mis à jour !";
                    }
                    if (!empty($request->get('nom')) && !empty($request->get('article_id'))) {
                        if ($sqlArticle !== "not good") {
                            return $this->json(['code' => 200, 'message' => $success, 'articleId' => $sqlArticle], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $magasin = $this->getDoctrine()->getRepository(Magasin::class)->deleteMagasin($request->get('id'));
                    // $article = $entityManager->getRepository(Magasin::class)->find($request->get('id'));
                    // $entityManager->remove($article);
                    // $entityManager->flush();
                    // $errors = $validator->validate($article);
                    if ($magasin == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce magasin", 'id' => $request->get('id')], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('nom')) && !empty($request->get('ville')) && !empty($request->get('magasin_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('magasin_id') == "new") {
                        $sqlMagasin = $this->getDoctrine()->getRepository(Magasin::class)->createMagasin($request->get('nom'), $image, "null", $request->get('adresse'), $request->get('ville'));
                        // $sqlMagasin = new Magasin();
                        // $sqlMagasin->setNom($request->get('nom'))
                        //     ->setImage($image)
                        //     ->setLocation("null")
                        //     ->setAdresse($request->get('adresse'))
                        //     ->setVille($request->get('ville'))
                        //     ->setCreatedAt(new \DateTime());
                        // $entityManager->persist($sqlMagasin);
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlMagasin);
                        // $id = $sqlMagasin->getId();
                        $success = "Le magasin à bien été créer !";
                    } else {
                        $sqlMagasin = $this->getDoctrine()->getRepository(Magasin::class)->saveMagasin($request->get('magasin_id'), $request->get('nom'), $request->get('image'), $request->get('adresse'), $request->get('ville'));
                        // $magasin = $entityManager->getRepository(Magasin::class)->find($request->get('magasin_id'));
                        // $magasin->setNom($request->get('nom'))
                        //     ->setImage($request->get('image'))
                        //     ->setAdresse($request->get('adresse'))
                        //     ->setVille($request->get('ville'));
                        // $entityManager->flush();
                        // $errors = $validator->validate($magasin);
                        // $id = $request->get('magasin_id');
                        $success = "Le magasin à bien été mis à jour !";
                    }
                    if (!empty($request->get('nom')) && !empty($request->get('magasin_id'))) {
                        if ($sqlMagasin !== "not good") {
                            return $this->json(['code' => 200, 'message' => $success, 'magasinId' => $sqlMagasin], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($request->get('id'));
                    // $article = $entityManager->getRepository(Restaurant::class)->find($request->get('id'));
                    // $entityManager->remove($article);
                    // $entityManager->flush();
                    // $errors = $validator->validate($article);
                    if ($restaurant == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce restaurant", 'id' => $request->get('id')], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('nom')) && !empty($request->get('ville')) && !empty($request->get('restaurant_id')) && !empty($request->get('contenu'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('restaurant_id') == "new") {
                        $sqlRestaurant = $this->getDoctrine()->getRepository(Restaurant::class)->createRestaurant($request->get('nom'), $image, "null", $request->get('adresse'), $request->get('ville'), $request->get('contenu'));
                        // $sqlRestaurant = new Restaurant();
                        // $sqlRestaurant->setNom($request->get('nom'))
                        //     ->setImage($image)
                        //     ->setLocation("null")
                        //     ->setAdresse($request->get('adresse'))
                        //     ->setVille($request->get('ville'))
                        //     ->setContenu($request->get('contenu'))
                        //     ->setCreatedAt(new \DateTime());
                        // $entityManager->persist($sqlRestaurant);
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlRestaurant);
                        // $id = $sqlRestaurant->getId();
                        $success = "Le restaurant à bien été créer !";
                    } else {
                        $sqlRestaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($request->get('restaurant_id'), $request->get('nom'), $request->get('image'), $request->get('adresse'), $request->get('ville'), $request->get('contenu'));
                        // $restaurant = $entityManager->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                        // $restaurant->setNom($request->get('nom'))
                        //     ->setImage($request->get('image'))
                        //     ->setAdresse($request->get('adresse'))
                        //     ->setContenu($request->get('contenu'))
                        //     ->setVille($request->get('ville'));
                        // $entityManager->flush();
                        // $errors = $validator->validate($restaurant);
                        // $id = $request->get('restaurant_id');
                        $success = "Le restaurant à bien été mis à jour !";
                    }
                    if (!empty($request->get('nom')) && !empty($request->get('restaurant_id')) && !empty($request->get('contenu'))) {
                        if ($sqlRestaurant !== "not good") {
                            return $this->json(['code' => 200, 'message' => $success, 'restaurantId' => $sqlRestaurant], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $produit = $this->getDoctrine()->getRepository(Produit::class)->deleteProduit($request->get('id'));
                    // $article = $entityManager->getRepository(Produit::class)->find($request->get('id'));
                    // $entityManager->remove($article);
                    // $entityManager->flush();
                    // $errors = $validator->validate($article);
                    if ($produit == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce produit", 'id' => $request->get('id')], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('nom')) && !empty($request->get('produit_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('produit_id') == "new") {
                        $sqlProduit = $this->getDoctrine()->getRepository(Produit::class)->createProduit($request->get('nom'), $image);
                        // $sqlProduit = new Produit();
                        // $sqlProduit->setNom($request->get('nom'))
                        //     ->setImage($image)
                        //     ->setCreatedAt(new \DateTime());
                        // $entityManager->persist($sqlProduit);
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlProduit);
                        // $id = $sqlProduit->getId();
                        $success = "Le produit à bien été créer !";
                    } else {
                        $sqlProduit = $this->getDoctrine()->getRepository(Produit::class)->saveProduit($request->get('produit_id'), $request->get('nom'), $image);
                        // $produit = $entityManager->getRepository(Produit::class)->find($request->get('produit_id'));
                        // $produit->setNom($request->get('nom'))
                        //     ->setImage($request->get('image'));
                        // $entityManager->flush();
                        // $errors = $validator->validate($produit);
                        // $id = $request->get('produit_id');
                        $success = "Le produit à bien été mis à jour !";
                    }
                    if (!empty($request->get('nom')) && !empty($request->get('produit_id'))) {
                        if ($sqlProduit !== "not good") {
                            return $this->json(['code' => 200, 'message' => $success, 'produitId' => $sqlProduit], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $repas = $this->getDoctrine()->getRepository(Repas::class)->deleteRepas($request->get('id'));
                    // $repas = $entityManager->getRepository(Repas::class)->find($request->get('id'));
                    // $entityManager->remove($article);
                    // $entityManager->flush();
                    // $errors = $validator->validate($article);
                    if ($repas == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce repas", 'id' => $request->get('id')], 200);
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
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('nom')) && !empty($request->get('repas_id')) && !empty($request->get('recette'))) {
                    $user = $security->getUser();
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = $request->get('image');
                    }
                    if ($request->get('repas_id') == "new") {
                        $sqlRepas = $this->getDoctrine()->getRepository(Repas::class)->createRepas($request->get('nom'), $image, $request->get('recette'), $user);
                        // $sqlRepas = new Repas();
                        // $sqlRepas->setNom($request->get('nom'))
                        //     ->setImage($image)
                        //     ->setRecette($request->get('recette'))
                        //     ->setPostedBy($user)
                        //     ->setCreatedAt(new \DateTime());
                        // $entityManager->persist($sqlRepas);
                        // $entityManager->flush();
                        // $errors = $validator->validate($sqlRepas);
                        // $id = $sqlRepas->getId();
                        $success = "Le repas à bien été créer !";
                    } else {
                        $sqlRepas = $this->getDoctrine()->getRepository(Repas::class)->saveRepas($request->get('repas_id'), $request->get('nom'), $image, $request->get('recette'), $user);
                        // $repas = $entityManager->getRepository(Repas::class)->find($request->get('repas_id'));
                        // $repas->setNom($request->get('nom'))
                        //     ->setRecette($request->get('recette'))
                        //     ->setImage($request->get('image'));
                        // $entityManager->flush();
                        // $errors = $validator->validate($repas);
                        // $id = $request->get('repas_id');
                        $success = "Le repas à bien été mis à jour !";
                    }
                    if (!empty($request->get('nom')) && !empty($request->get('repas_id'))) {
                        if ($sqlRepas !== "not good") {
                            return $this->json(['code' => 200, 'message' => $success, 'repasId' => $sqlRepas], 200);
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

    /**
     * @Route("/admin/deleteRestaurantAvis", name="admin_delete_restaurantavis")
     */
    public function deleteRestaurantAvis(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $restaurantAvis = $this->getDoctrine()->getRepository(RestaurantAvis::class)->deleteRestaurantAvis($request->get('id'));
                    // $deleteRestaurantAvis = $entityManager->getRepository(RestaurantAvis::class)->find($request->get('id'));
                    // $entityManager->remove($deleteRestaurantAvis);
                    // $entityManager->flush();
                    // $errors = $validator->validate($deleteRestaurantAvis);
                    if ($restaurantAvis == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet avis", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'avis...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/user/{id}/edit", name="admin_edit_user")
     */
    public function editUser(User $user)
    {
        return $this->render('admin/edit/user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/saveUser", name="admin_save_user")
     */
    public function saveUser(ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, Security $security, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-user', $submittedToken)) {
                if (!empty($request->get('username')) && !empty($request->get('email'))) {
                    $erreur = false;
                    $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserProfil($request->get('user_id'), $request->get('username'), $request->get('email'), $request->get('role'), $request->get('bio'));
                    // $userSql = $entityManager->getRepository(User::class)->find($request->get('user_id'));
                    // $userSql->setUsername($request->get('username'))
                    //     ->setEmail($request->get('email'))
                    //     ->setRole($request->get('role'))
                    //     ->setBio($request->get('bio'));
                    // $entityManager->flush();
                    if ($userSql !== "good") {
                        $erreur = true;
                    }
                    if ($request->get('deleteAvatar') == true) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($request->get('user_id'), "");
                        if ($userSql !== "good") {
                            $erreur = true;
                        }
                        // $userSql = $entityManager->getRepository(User::class)->find($request->get('user_id'));
                        // $userSql->setAvatar("");
                        // $entityManager->flush();
                    }
                    if (!empty($request->get('motdepasse'))) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserPassword($request->get('user_id'), $request->get('motdepasse'));
                        if ($userSql !== "good") {
                            $erreur = true;
                        }
                        // $sqlUser = new User();
                        // $password = $passwordEncoder->encodePassword($sqlUser, $request->get('motdepasse'));
                        // $userSql = $entityManager->getRepository(User::class)->find($request->get('user_id'));
                        // $userSql->setPassword($password);
                        // $entityManager->flush();
                    }
                    $id = $request->get('user_id');
                    $success = "L'utilisateur à bien été mis à jour !";
                    if (!$erreur) {
                        return $this->json(['code' => 200, 'message' => $success, 'userId' => $id], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour de l\'utilisateur...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/deleteUser", name="admin_delete_user")
     */
    public function deleteUser(ValidatorInterface $validator, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $deleteUser = $this->getDoctrine()->getRepository(User::class)->deleteUser($request->get('id'));
                    // $deleteUser = $entityManager->getRepository(User::class)->find($request->get('id'));
                    // $entityManager->remove($deleteUser);
                    // $entityManager->flush();
                    // $errors = $validator->validate($deleteUser);
                    if ($deleteUser == "good") {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet avis", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'avis...'], 200);
                }
            }
        }
    }
}
