<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Repas;
use App\Entity\Article;
use App\Entity\Magasin;
use App\Entity\Produit;
use App\Entity\Restaurant;
use App\Entity\RestaurantAvis;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function deleteArticle(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $article = $this->getDoctrine()->getRepository(Article::class)->deleteArticle($request->get('id'));
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
    public function saveArticle(Request $request): Response
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
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->createArticle($request->get('nom'), $request->get('contenu'), $image);
                        $id = $sqlArticle;
                        $success = "L'article à bien été créer !";
                    } else {
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->saveArticle($request->get('article_id'), $request->get('nom'), $request->get('contenu'), $image);
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
    public function deleteMagasin(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $magasin = $this->getDoctrine()->getRepository(Magasin::class)->deleteMagasin($request->get('id'));
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
    public function saveMagasin(Request $request): Response
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
                        $success = "Le magasin à bien été créer !";
                    } else {
                        $sqlMagasin = $this->getDoctrine()->getRepository(Magasin::class)->saveMagasin($request->get('magasin_id'), $request->get('nom'), $request->get('image'), $request->get('adresse'), $request->get('ville'));
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
    public function deleteRestaurant(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($request->get('id'));
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
    public function saveRestaurant(Request $request): Response
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
                        $success = "Le restaurant à bien été créer !";
                    } else {
                        $sqlRestaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($request->get('restaurant_id'), $request->get('nom'), $request->get('image'), $request->get('adresse'), $request->get('ville'), $request->get('contenu'));
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
    public function deleteProduit(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $produit = $this->getDoctrine()->getRepository(Produit::class)->deleteProduit($request->get('id'));
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
    public function saveProduit(Request $request): Response
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
                        $success = "Le produit à bien été créer !";
                    } else {
                        $sqlProduit = $this->getDoctrine()->getRepository(Produit::class)->saveProduit($request->get('produit_id'), $request->get('nom'), $image);
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
    public function deleteRepas(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $repas = $this->getDoctrine()->getRepository(Repas::class)->deleteRepas($request->get('id'));
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
    public function saveRepas(Security $security, Request $request): Response
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
                        $success = "Le repas à bien été créer !";
                    } else {
                        $sqlRepas = $this->getDoctrine()->getRepository(Repas::class)->saveRepas($request->get('repas_id'), $request->get('nom'), $image, $request->get('recette'), $user);
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
    public function deleteRestaurantAvis(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $restaurantAvis = $this->getDoctrine()->getRepository(RestaurantAvis::class)->deleteRestaurantAvis($request->get('id'));
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
    public function saveUser(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-user', $submittedToken)) {
                if (!empty($request->get('username')) && !empty($request->get('email'))) {
                    $erreur = false;
                    $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserProfil($request->get('user_id'), $request->get('username'), $request->get('email'), $request->get('role'), $request->get('bio'));
                    if ($userSql !== "good") {
                        $erreur = true;
                    }
                    if ($request->get('deleteAvatar') == true) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($request->get('user_id'), "");
                        if ($userSql !== "good") {
                            $erreur = true;
                        }
                    }
                    if (!empty($request->get('motdepasse'))) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserPassword($request->get('user_id'), $request->get('motdepasse'));
                        if ($userSql !== "good") {
                            $erreur = true;
                        }
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
    public function deleteUser(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $deleteUser = $this->getDoctrine()->getRepository(User::class)->deleteUser($request->get('id'));
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
