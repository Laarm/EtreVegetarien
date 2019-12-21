<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Meal;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Store;
use App\Entity\Product;
use App\Entity\Restaurant;
use App\Entity\ProductSync;
use App\Entity\RestaurantFeedback;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminEditController extends AbstractController
{
    public function index()
    {
        return $this->render('admin/edit/index.html.twig');
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
    public function deleteArticle(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(Article::class)->find($request->get('id'));
                    if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                    }
                    $article = $this->getDoctrine()->getRepository(Article::class)->deleteArticle($request->get('id'));
                    if ($article) {
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
    public function saveArticle(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('contenu')) && !empty($request->get('article_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('article_id') == "new") {
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->createArticle($request->get('name'), $request->get('contenu'), $image);
                        $success = "L'article à bien été créer !";
                    } else {
                        $ancienneImage = $this->getDoctrine()->getRepository(Article::class)->find($request->get('article_id'));
                        if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                        }
                        $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->saveArticle($request->get('article_id'), $request->get('name'), $request->get('contenu'), $image);
                        $success = "L'article à bien été mis à jour !";
                    }
                    if ($sqlArticle) {
                        return $this->json(['code' => 200, 'message' => $success, 'articleId' => $sqlArticle], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/store/{id}/edit", name="admin_edit_store")
     */
    public function editStore(Store $store)
    {
        return $this->render('admin/edit/store.html.twig', [
            'store' => $store,
        ]);
    }

    /**
     * @Route("/admin/store/new", name="admin_new_store")
     */
    public function newStore()
    {
        return $this->render('admin/edit/newStore.html.twig');
    }

    /**
     * @Route("/admin/deleteStore", name="admin_delete_store")
     */
    public function deleteStore(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(Store::class)->find($request->get('id'));
                    if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                    }
                    $store = $this->getDoctrine()->getRepository(Store::class)->deleteStore($request->get('id'));
                    if ($store) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce store", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du store...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveStore", name="admin_save_store")
     */
    public function saveStore(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('ville')) && !empty($request->get('store_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('store_id') == "new") {
                        $sqlStore = $this->getDoctrine()->getRepository(Store::class)->createStore($request->get('name'), $image, "null", $request->get('adresse'), $request->get('ville'));
                        $success = "Le magasin à bien été créer !";
                    } else {
                        $ancienneImage = $this->getDoctrine()->getRepository(Store::class)->find($request->get('store_id'));
                        if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                        }
                        $sqlStore = $this->getDoctrine()->getRepository(Store::class)->saveStore($request->get('store_id'), $request->get('name'), $request->get('image'), $request->get('adresse'), $request->get('ville'));
                        $success = "Le magasin à bien été mis à jour !";
                    }
                    if ($sqlStore) {
                        return $this->json(['code' => 200, 'message' => $success, 'storeId' => $sqlStore], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
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
    public function deleteRestaurant(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('id'));
                    if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                    }
                    $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($request->get('id'));
                    if ($restaurant) {
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
    public function saveRestaurant(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('ville')) && !empty($request->get('restaurant_id')) && !empty($request->get('contenu'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('restaurant_id') == "new") {
                        $sqlRestaurant = $this->getDoctrine()->getRepository(Restaurant::class)->createRestaurant($request->get('name'), $image, "null", $request->get('adresse'), $request->get('ville'), $request->get('contenu'));
                        $success = "Le restaurant à bien été créer !";
                    } else {
                        $ancienneImage = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                        if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                        }
                        $sqlRestaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($request->get('restaurant_id'), $request->get('name'), $request->get('image'), $request->get('adresse'), $request->get('ville'), $request->get('contenu'));
                        $success = "Le restaurant à bien été mis à jour !";
                    }
                    if (!empty($request->get('name')) && !empty($request->get('restaurant_id')) && !empty($request->get('contenu'))) {
                        if ($sqlRestaurant) {
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
     * @Route("/admin/product/{id}/edit", name="admin_edit_product")
     */
    public function editProduct(Product $product)
    {
        return $this->render('admin/edit/product.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * @Route("/admin/product/new", name="admin_new_product")
     */
    public function newProduct()
    {
        return $this->render('admin/edit/newProduct.html.twig');
    }

    /**
     * @Route("/admin/deleteProduct", name="admin_delete_product")
     */
    public function deleteProduct(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
                    if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                    }
                    $product = $this->getDoctrine()->getRepository(Product::class)->deleteProduct($request->get('id'));
                    if ($product) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce product", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du product...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveProduct", name="admin_save_product")
     */
    public function saveProduct(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('product_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('product_id') == "new") {
                        $sqlProduct = $this->getDoctrine()->getRepository(Product::class)->createProduct($request->get('name'), $image);
                        $success = "Le produit à bien été créer !";
                    } else {
                        $ancienneImage = $this->getDoctrine()->getRepository(Product::class)->find($request->get('product_id'));
                        if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                        }
                        $sqlProduct = $this->getDoctrine()->getRepository(Product::class)->saveProduct($request->get('product_id'), $request->get('name'), $image);
                        $success = "Le produit à bien été mis à jour !";
                    }
                    if ($sqlProduct) {
                        return $this->json(['code' => 200, 'message' => $success, 'productId' => $sqlProduct], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/meal/{id}/edit", name="admin_edit_meal")
     */
    public function editMeal(Meal $meal)
    {
        return $this->render('admin/edit/meal.html.twig', [
            'meal' => $meal,
        ]);
    }

    /**
     * @Route("/admin/meal/new", name="admin_new_meal")
     */
    public function newMeal()
    {
        return $this->render('admin/edit/newMeal.html.twig');
    }

    /**
     * @Route("/admin/deleteMeal", name="admin_delete_meal")
     */
    public function deleteMeal(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('id'));
                    if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                    }
                    $meal = $this->getDoctrine()->getRepository(Meal::class)->deleteMeal($request->get('id'));
                    if ($meal) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce meal", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du meal...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveMeal", name="admin_save_meal")
     */
    public function saveMeal(Security $security, Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('meal_id')) && !empty($request->get('recette'))) {
                    $user = $security->getUser();
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = $request->get('image');
                    }
                    if ($request->get('meal_id') == "new") {
                        $sqlMeal = $this->getDoctrine()->getRepository(Meal::class)->createMeal($request->get('name'), $image, $request->get('recette'), $user);
                        $success = "Le repas à bien été créer !";
                    } else {
                        $ancienneImage = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('meal_id'));
                        if (substr($ancienneImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getImage()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getImage(), 'activity.log']);
                        }
                        $sqlMeal = $this->getDoctrine()->getRepository(Meal::class)->saveMeal($request->get('meal_id'), $request->get('name'), $image, $request->get('recette'), $user);
                        $success = "Le repas à bien été mis à jour !";
                    }
                    if ($sqlMeal) {
                        return $this->json(['code' => 200, 'message' => $success, 'mealId' => $sqlMeal], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Veuillez remplir tout les champs !'], 200);
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
     * @Route("/admin/deleteRestaurantFeedback", name="admin_delete_restaurantfeedback")
     */
    public function deleteRestaurantFeedback(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $restaurantFeedback = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->deleteRestaurantFeedback($request->get('id'));
                    if ($restaurantFeedback) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet feedback", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'feedback...'], 200);
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
    public function saveUser(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-user', $submittedToken)) {
                if (!empty($request->get('username')) && !empty($request->get('email'))) {
                    $erreur = false;
                    $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserProfil($request->get('user_id'), $request->get('username'), $request->get('email'), $request->get('role'), $request->get('bio'));
                    if (!$userSql) {
                        $erreur = true;
                    }
                    if ($request->get('deleteAvatar') == true) {
                        $ancienneImage = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));
                        if (!empty($ancienneImage->getAvatar()) && substr($ancienneImage->getAvatar(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getAvatar()) {
                            $filesystem->remove(['symlink', "../public/" . $ancienneImage->getAvatar(), 'activity.log']);
                        }
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($request->get('user_id'), "");
                        if (!$userSql) {
                            $erreur = true;
                        }
                    }
                    if (!empty($request->get('motdepasse'))) {
                        $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserPassword($request->get('user_id'), $request->get('motdepasse'));
                        if (!$userSql) {
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
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour de l\'user...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/deleteUser", name="admin_delete_user")
     */
    public function deleteUser(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $ancienneImage = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
                    if (substr($ancienneImage->getAvatar(), 0, 4) !== "http" && $request->get('image') !== $ancienneImage->getAvatar()) {
                        $filesystem->remove(['symlink', "../public/" . $ancienneImage->getAvatar(), 'activity.log']);
                    }
                    $deleteUser = $this->getDoctrine()->getRepository(User::class)->deleteUser($request->get('id'));
                    if ($deleteUser) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet feedback", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'feedback...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/deleteProductStore", name="admin_delete_product_store")
     */
    public function deleteProductStore(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $store = $this->getDoctrine()->getRepository(ProductSync::class)->deleteProductStore($request->get('id'));
                    if ($store) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce product du store", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du store...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/admin/addProductSync", name="admin_add_product_store")
     */
    public function addProductSync(Security $security, Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('storeId')) && !empty($request->get('productId'))) {
                    $sqlProduct = $this->getDoctrine()->getRepository(ProductSync::class)->createProductStore($request->get('storeId'), $request->get('productId'));
                    if ($sqlProduct) {
                        return $this->json(['code' => 200, 'message' => "Le produit à bien été ajouter au store !", 'mealId' => $sqlProduct], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la mise à jour du meal...'], 200);
                }
            }
        }
    }

    /**
     * @Route("/products/search", name="product_search")
     */
    public function searchProduct(Request $request): Response
    {
        $search = htmlspecialchars($request->get('search'));
        if (!empty($search)) {
            $products = $this->getDoctrine()->getRepository(Product::class)->searchProduct($search, 3);
            return $this->json($products, 200);
        }
        if ($search == "") {
            $products = $this->getDoctrine()->getRepository(Product::class)->getAllProduct($search, 3);
            return $this->json($products, 200);
        }
        return $this->json([], 200);
    }

    /**
     * @Route("/admin/deleteContact", name="admin_delete_contact")
     */
    public function deleteContact(Request $request, Filesystem $filesystem): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $article = $this->getDoctrine()->getRepository(Contact::class)->deleteContact($request->get('id'));
                    if ($article) {
                        return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce message", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
                    }
                } else {
                    return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de du contact...'], 200);
                }
            }
        }
    }
}
