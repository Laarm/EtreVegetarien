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
    public function deleteArticle(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-article', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(Article::class)->find($request->get('id'));
                    if (substr($oldImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getImage(), 'activity.log']);
                    }
                    $sqlArticle = $this->getDoctrine()->getRepository(Article::class)->find($request->get('id'));
                    $errors = $validator->validate($sqlArticle);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Article::class)->deleteArticle($sqlArticle);
                        return $this->json(['message' => "Vous avez bien supprimer cet article", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression de l\'article...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveArticle", name="admin_save_article")
     */
    public function saveArticle(Request $request, Filesystem $filesystem, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                $error = false;
                if (!empty($request->get('name')) && !empty($request->get('content')) && !empty($request->get('article_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('article_id') == "new") {
                        $article = new Article();
                        $article->setName($request->get('name'))
                            ->setContent($request->get('content'))
                            ->setImage($image)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($article);
                        if (count($errors) == 0) {
                            $article = $this->getDoctrine()->getRepository(Article::class)->createArticle($article);
                            return $this->json(['message' => "L'article à bien été créer !", 'articleId' => $article], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    } else {
                        $article = $em->getRepository(Article::class)->find($request->get('article_id'));
                        $oldImage = $article->getImage();
                        $article->setName($request->get('name'))
                            ->setContent($request->get('content'))
                            ->setImage($image);
                        $errors = $validator->validate($article);
                        if (count($errors) == 0) {
                            if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                                $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                            }
                            $article = $this->getDoctrine()->getRepository(Article::class)->saveArticle($article);
                            return $this->json(['message' => "L'article à bien été mis à jour !", 'articleId' => $article->getId()], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                } else {
                    return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
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
    public function deleteStore(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-store', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(Store::class)->find($request->get('id'));
                    if (substr($oldImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getImage(), 'activity.log']);
                    }
                    $sqlStore = $this->getDoctrine()->getRepository(Store::class)->find($request->get('id'));
                    $errors = $validator->validate($sqlStore);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Store::class)->deleteStore($sqlStore);
                        return $this->json(['message' => "Vous avez bien supprimer ce store", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression du store...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveStore", name="admin_save_store")
     */
    public function saveStore(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('city')) && !empty($request->get('store_id'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('store_id') == "new") {
                        $sqlStore = new Store();
                        $sqlStore->setName($request->get('name'))
                            ->setImage($image)
                            ->setLocation("null")
                            ->setAddress($request->get('address'))
                            ->setCity($request->get('city'))
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($sqlStore);
                        if (count($errors) == 0) {
                            $storeId = $this->getDoctrine()->getRepository(Store::class)->createStore($sqlStore);
                            return $this->json(['message' => "Le magasin à bien été créer !", 'storeId' => $storeId], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    } else {
                        $sqlStore = $this->getDoctrine()->getRepository(Store::class)->find($request->get('store_id'));
                        $oldImage = $sqlStore->getImage();
                        $sqlStore->setName($request->get('name'))
                            ->setImage($image)
                            ->setAddress($request->get('address'))
                            ->setCity($request->get('city'));
                        $errors = $validator->validate($sqlStore);
                        if (count($errors) == 0) {
                            if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                                $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                            }
                            $storeId = $this->getDoctrine()->getRepository(Store::class)->saveStore($sqlStore);
                            return $this->json(['message' => "Le magasin à bien été mis à jour !", 'storeId' => $storeId], 200);
                        } else {
                        }
                    }
                } else {
                    return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
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
    public function deleteRestaurant(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-restaurant', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('id'));
                    if (substr($oldImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getImage(), 'activity.log']);
                    }
                    $sql = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($sql);
                        return $this->json(['message' => "Vous avez bien supprimer ce restaurant", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression du restaurant...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveRestaurant", name="admin_save_restaurant")
     */
    public function saveRestaurant(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('city')) && !empty($request->get('restaurant_id')) && !empty($request->get('content'))) {
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = htmlspecialchars($request->get('image'));
                    }
                    if ($request->get('restaurant_id') == "new") {
                        $restaurant = new Restaurant();
                        $restaurant->setName($request->get('name'))
                            ->setImage($image)
                            ->setLocation("null")
                            ->setAddress($request->get('address'))
                            ->setCity($request->get('city'))
                            ->setContent($request->get('content'))
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($restaurant);
                        if (count($errors) == 0) {
                            $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->createRestaurant($restaurant);
                            return $this->json(['message' => "Le restaurant à bien été créer !", 'restaurantId' => $restaurant], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    } else {
                        $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                        $oldImage = $restaurant->getImage();
                        $restaurant->setName($request->get('name'))
                            ->setImage($image)
                            ->setAddress($request->get('address'))
                            ->setCity($request->get('city'))
                            ->setContent($request->get('content'));
                        $errors = $validator->validate($restaurant);
                        if (count($errors) == 0) {
                            if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                                $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                            }
                            $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($restaurant);
                            return $this->json(['message' => "Le restaurant à bien été mis à jour !", 'restaurantId' => $restaurant], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la mise à jour du restaurant...'], 400);
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
    public function deleteProduct(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-product', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
                    if (substr($oldImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getImage(), 'activity.log']);
                    }
                    $sql = $this->getDoctrine()->getRepository(Product::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Product::class)->deleteProduct($sql);
                        return $this->json(['message' => "Vous avez bien supprimer ce produit", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression du produit...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveProduct", name="admin_save_product")
     */
    public function saveProduct(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
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
                        $product = new Product();
                        $product->setName($request->get('name'))
                            ->setImage($image)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($product);
                        if (count($errors) == 0) {
                            $product = $this->getDoctrine()->getRepository(Product::class)->createProduct($product);
                            return $this->json(['message' => "Le produit à bien été créer !", 'productId' => $product], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    } else {
                        $product = $this->getDoctrine()->getRepository(Product::class)->find($request->get('product_id'));
                        $oldImage = $product->getImage();
                        $product->setName($request->get('name'))
                            ->setImage($image);
                        $errors = $validator->validate($product);
                        if (count($errors) == 0) {
                            if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                                $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                            }
                            $product = $this->getDoctrine()->getRepository(Product::class)->saveProduct($product);
                            return $this->json(['message' => "Le produit à bien été mis à jour !", 'productId' => $product->getId()], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                } else {
                    return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
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
    public function deleteMeal(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-meal', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('id'));
                    if (substr($oldImage->getImage(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getImage()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getImage(), 'activity.log']);
                    }
                    $sql = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Meal::class)->deleteMeal($sql);
                        return $this->json(['message' => "Vous avez bien supprimer ce repas", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression du repas...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/saveMeal", name="admin_save_meal")
     */
    public function saveMeal(Security $security, Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('name')) && !empty($request->get('meal_id')) && !empty($request->get('recipe'))) {
                    $user = $security->getUser();
                    if (empty($request->get('image'))) {
                        $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
                    } else {
                        $image = $request->get('image');
                    }
                    if ($request->get('meal_id') == "new") {
                        $meal = new Meal();
                        $meal->setName($request->get('name'))
                            ->setImage($image)
                            ->setRecipe($request->get('recipe'))
                            ->setPostedBy($user)
                            ->setCreatedAt(new \DateTime());
                        $errors = $validator->validate($meal);
                        if (count($errors) == 0) {
                            $meal = $this->getDoctrine()->getRepository(Meal::class)->createMeal($meal);
                            return $this->json(['message' => "Le repas à bien été créer !", 'mealId' => $meal], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    } else {
                        $meal = $this->getDoctrine()->getRepository(Meal::class)->find($request->get('meal_id'));
                        $oldImage = $meal->getImage();
                        $meal->setName($request->get('name'))
                            ->setImage($image)
                            ->setRecipe($request->get('recipe'))
                            ->setPostedBy($user);
                        $errors = $validator->validate($meal);
                        if (count($errors) == 0) {
                            if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                                $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                            }
                            $meal = $this->getDoctrine()->getRepository(Meal::class)->saveMeal($meal);
                            return $this->json(['message' => "Le repas à bien été mis à jour !", 'mealId' => $meal->getId()], 200);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                } else {
                    return $this->json(['message' => 'Veuillez remplir tout les champs !'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/uploadImage", name="admin_upload_image")
     */
    public function uploadImage(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('_token');
            if ($this->isCsrfTokenValid('upload-image', $submittedToken)) {
                $uploadedFile = $request->files->get('file');
                $imageConstraint = new Assert\Image([
                    "maxSize" => "2k"
                ]);
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

    /**
     * @Route("/admin/deleteRestaurantFeedback", name="admin_delete_restaurantfeedback")
     */
    public function deleteRestaurantFeedback(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-restaurantfeedback', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $sql = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(RestaurantFeedback::class)->deleteRestaurantFeedback($sql);
                        return $this->json(['message' => "Vous avez bien supprimer cet avis", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression de l\'avis...'], 400);
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
    public function saveUser(Request $request, Filesystem $filesystem, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-user', $submittedToken)) {
                if (!empty($request->get('username')) && !empty($request->get('email'))) {
                    $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));
                    $sqlUser->setUsername($request->get('username'))
                        ->setEmail($request->get('email'))
                        ->setRole($request->get('role'))
                        ->setBio($request->get('bio'));
                    $errors = $validator->validate($sqlUser);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                    if ($request->get('deleteAvatar') == true) {
                        $oldImage = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));
                        if (!empty($oldImage->getAvatar()) && substr($oldImage->getAvatar(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getAvatar()) {
                            $filesystem->remove(['symlink', "../public/" . $oldImage->getAvatar(), 'activity.log']);
                        }
                        $oldImage->setAvatar(null);
                        $errors = $validator->validate($oldImage);
                        if (count($errors) == 0) {
                            $this->getDoctrine()->getRepository(User::class)->saveUserAvatar($oldImage);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                    if (!empty($request->get('motdepasse'))) {
                        $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));
                        $password = $passwordEncoder->encodePassword($sqlUser, $request->get('motdepasse'));
                        $sqlUser->setPassword($password);
                        $errors = $validator->validate($sqlUser);
                        if (count($errors) == 0) {
                            $userSql = $this->getDoctrine()->getRepository(User::class)->saveUserPassword($sqlUser);
                        } else {
                            return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                        }
                    }
                    $success = "L'utilisateur à bien été mis à jour !";
                    return $this->json(['message' => $success, 'userId' => $request->get('user_id')], 200);
                } else {
                    return $this->json(['message' => 'Erreur lors de la mise à jour de l\'user...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/deleteUser", name="admin_delete_user")
     */
    public function deleteUser(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-user', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $oldImage = $this->getDoctrine()->getRepository(User::class)->find($request->get('id'));
                    if (substr($oldImage->getAvatar(), 0, 4) !== "http" && $request->get('image') !== $oldImage->getAvatar()) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage->getAvatar(), 'activity.log']);
                    }
                    $errors = $validator->validate($oldImage);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(User::class)->deleteUser($oldImage);
                        return $this->json(['message' => "Vous avez bien supprimer cet utilisateur", 'id' => $request->get('id')], 200);
                    } else {
                        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                    }
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression de l\'utilisateur...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/deleteProductStore", name="admin_delete_product_store")
     */
    public function deleteProductStore(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-productsync', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $sql = $this->getDoctrine()->getRepository(ProductSync::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(ProductSync::class)->deleteProductStore($sql);
                        return $this->json(['message' => "Vous avez bien supprimer ce product du store", 'id' => $request->get('id')], 200);
                    }
                    return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression du store...'], 400);
                }
            }
        }
    }

    /**
     * @Route("/admin/addProductSync", name="admin_add_product_store")
     */
    public function addProductSync(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('save-item', $submittedToken)) {
                if (!empty($request->get('storeId')) && !empty($request->get('productId'))) {
                    $storeId = $this->getDoctrine()->getRepository(Store::class)->find($request->get('storeId'));
                    $productId = $this->getDoctrine()->getRepository(Product::class)->find($request->get('productId'));
                    $sql = new ProductSync();
                    $sql->setStore($storeId)
                        ->setProduct($productId)
                        ->setCreatedAt(new \DateTime());
                    $this->getDoctrine()->getRepository(ProductSync::class)->createProductStore($sql);
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        return $this->json(['message' => "Le produit à bien été ajouter au store !", 'productId' => $sql->getId()], 200);
                    }
                    return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                } else {
                    return $this->json(['message' => 'Erreur lors de la mise à jour du meal...'], 400);
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
    public function deleteContact(Request $request, ValidatorInterface $validator): Response
    {
        if ($request->isXmlHttpRequest()) {
            $submittedToken = $request->get('csrfData');
            if ($this->isCsrfTokenValid('delete-contact', $submittedToken)) {
                if (!empty($request->get('id'))) {
                    $sql = $this->getDoctrine()->getRepository(Contact::class)->find($request->get('id'));
                    $errors = $validator->validate($sql);
                    if (count($errors) == 0) {
                        $this->getDoctrine()->getRepository(Contact::class)->deleteContact($sql);
                        return $this->json(['message' => "Vous avez bien supprimer ce message", 'id' => $request->get('id')], 200);
                    }
                    return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
                } else {
                    return $this->json(['message' => 'Erreur lors de la suppression de du contact...'], 400);
                }
            }
        }
    }
}
