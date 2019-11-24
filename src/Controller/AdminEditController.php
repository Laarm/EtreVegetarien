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
    public function deleteArticle(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['id'])) {
            $_POST['id'] = htmlspecialchars($_POST['id']);
            $article = $entityManager->getRepository(Article::class)->find($_POST['id']);
            $entityManager->remove($article);
            $entityManager->flush();
            $errors = $validator->validate($article);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer cet article", 'id' => $_POST['id']], 200);
            } else {
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        } else {
            return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression de l\'article...'], 200);
        }
    }

    /**
     * @Route("/admin/saveArticle", name="admin_save_article")
     */
    public function saveArticle(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['nom']) && isset($_POST['contenu']) && isset($_POST['article_id'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['article_id'] = htmlspecialchars($_POST['article_id']);
            if (empty($_POST['image'])) {
                $_POST['image'] = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            } else {
                $_POST['image'] = htmlspecialchars($_POST['image']);
            }
            if ($_POST['article_id'] == "new") {
                $sqlArticle = new Article();
                $sqlArticle->setNom($_POST['nom'])
                    ->setContenu($_POST['contenu'])
                    ->setImage($_POST['image'])
                    ->setCreatedAt(new \DateTime());
                $entityManager->persist($sqlArticle);
                $entityManager->flush();
                $errors = $validator->validate($sqlArticle);
                $id = $sqlArticle->getId();
                $success = "L'article à bien été créer !";
            } else {
                $article = $entityManager->getRepository(Article::class)->find($_POST['article_id']);
                $article->setNom($_POST['nom'])
                    ->setContenu($_POST['contenu'])
                    ->setImage($_POST['image']);
                $entityManager->flush();
                $errors = $validator->validate($article);
                $id = $_POST['article_id'];
                $success = "L'article à bien été mis à jour !";
            }
            if (!empty($_POST['nom']) && !empty($_POST['article_id'])) {
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
    public function deleteMagasin(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['id'])) {
            $_POST['id'] = htmlspecialchars($_POST['id']);
            $article = $entityManager->getRepository(Magasin::class)->find($_POST['id']);
            $entityManager->remove($article);
            $entityManager->flush();
            $errors = $validator->validate($article);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce magasin", 'id' => $_POST['id']], 200);
            } else {
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        } else {
            return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du magasin...'], 200);
        }
    }

    /**
     * @Route("/admin/saveMagasin", name="admin_save_magasin")
     */
    public function saveMagasin(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['nom']) && isset($_POST['ville']) && isset($_POST['magasin_id'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['magasin_id'] = htmlspecialchars($_POST['magasin_id']);
            if (empty($_POST['image'])) {
                $_POST['image'] = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            } else {
                $_POST['image'] = htmlspecialchars($_POST['image']);
            }
            if ($_POST['magasin_id'] == "new") {
                $sqlMagasin = new Magasin();
                $sqlMagasin->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setLocation("null")
                    ->setAdresse($_POST['adresse'])
                    ->setVille($_POST['ville'])
                    ->setCreatedAt(new \DateTime());
                $entityManager->persist($sqlMagasin);
                $entityManager->flush();
                $errors = $validator->validate($sqlMagasin);
                $id = $sqlMagasin->getId();
                $success = "Le magasin à bien été créer !";
            } else {
                $magasin = $entityManager->getRepository(Magasin::class)->find($_POST['magasin_id']);
                $magasin->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setAdresse($_POST['adresse'])
                    ->setVille($_POST['ville']);
                $entityManager->flush();
                $errors = $validator->validate($magasin);
                $id = $_POST['magasin_id'];
                $success = "Le magasin à bien été mis à jour !";
            }
            if (!empty($_POST['nom']) && !empty($_POST['magasin_id'])) {
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
    public function deleteRestaurant(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['id'])) {
            $_POST['id'] = htmlspecialchars($_POST['id']);
            $article = $entityManager->getRepository(Restaurant::class)->find($_POST['id']);
            $entityManager->remove($article);
            $entityManager->flush();
            $errors = $validator->validate($article);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce restaurant", 'id' => $_POST['id']], 200);
            } else {
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        } else {
            return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du restaurant...'], 200);
        }
    }

    /**
     * @Route("/admin/saveRestaurant", name="admin_save_restaurant")
     */
    public function saveRestaurant(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['nom']) && isset($_POST['ville']) && isset($_POST['restaurant_id'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['restaurant_id'] = htmlspecialchars($_POST['restaurant_id']);
            if (empty($_POST['image'])) {
                $_POST['image'] = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            } else {
                $_POST['image'] = htmlspecialchars($_POST['image']);
            }
            if ($_POST['restaurant_id'] == "new") {
                $sqlRestaurant = new Restaurant();
                $sqlRestaurant->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setLocation("null")
                    ->setAdresse($_POST['adresse'])
                    ->setVille($_POST['ville'])
                    ->setCreatedAt(new \DateTime());
                $entityManager->persist($sqlRestaurant);
                $entityManager->flush();
                $errors = $validator->validate($sqlRestaurant);
                $id = $sqlRestaurant->getId();
                $success = "Le magasin à bien été créer !";
            } else {
                $restaurant = $entityManager->getRepository(Restaurant::class)->find($_POST['restaurant_id']);
                $restaurant->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setAdresse($_POST['adresse'])
                    ->setVille($_POST['ville']);
                $entityManager->flush();
                $errors = $validator->validate($restaurant);
                $id = $_POST['restaurant_id'];
                $success = "Le restaurant à bien été mis à jour !";
            }
            if (!empty($_POST['nom']) && !empty($_POST['restaurant_id'])) {
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
    public function deleteProduit(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['id'])) {
            $_POST['id'] = htmlspecialchars($_POST['id']);
            $article = $entityManager->getRepository(Produit::class)->find($_POST['id']);
            $entityManager->remove($article);
            $entityManager->flush();
            $errors = $validator->validate($article);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce produit", 'id' => $_POST['id']], 200);
            } else {
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        } else {
            return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du produit...'], 200);
        }
    }

    /**
     * @Route("/admin/saveProduit", name="admin_save_produit")
     */
    public function saveProduit(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['nom']) && isset($_POST['produit_id'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['produit_id'] = htmlspecialchars($_POST['produit_id']);
            if (empty($_POST['image'])) {
                $_POST['image'] = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            } else {
                $_POST['image'] = htmlspecialchars($_POST['image']);
            }
            if ($_POST['produit_id'] == "new") {
                $sqlProduit = new Produit();
                $sqlProduit->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setCreatedAt(new \DateTime());
                $entityManager->persist($sqlProduit);
                $entityManager->flush();
                $errors = $validator->validate($sqlProduit);
                $id = $sqlProduit->getId();
                $success = "Le produit à bien été créer !";
            } else {
                $produit = $entityManager->getRepository(Produit::class)->find($_POST['produit_id']);
                $produit->setNom($_POST['nom'])
                    ->setImage($_POST['image']);
                $entityManager->flush();
                $errors = $validator->validate($produit);
                $id = $_POST['produit_id'];
                $success = "Le produit à bien été mis à jour !";
            }
            if (!empty($_POST['nom']) && !empty($_POST['produit_id'])) {
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
    public function deleteRepas(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if (isset($_POST['id'])) {
            $_POST['id'] = htmlspecialchars($_POST['id']);
            $article = $entityManager->getRepository(Repas::class)->find($_POST['id']);
            $entityManager->remove($article);
            $entityManager->flush();
            $errors = $validator->validate($article);
            if (count($errors) == 0) {
                return $this->json(['code' => 200, 'message' => "Vous avez bien supprimer ce repas", 'id' => $_POST['id']], 200);
            } else {
                return $this->json(['code' => 400, 'message' => 'Veuillez contacter un administrateur !'], 200);
            }
        } else {
            return $this->json(['code' => 400, 'message' => 'Erreur lors de la suppression du repas...'], 200);
        }
    }

    /**
     * @Route("/admin/saveRepas", name="admin_save_repas")
     */
    public function saveRepas(ValidatorInterface $validator, EntityManagerInterface $entityManager, Security $security): Response
    {
        if (isset($_POST['nom']) && isset($_POST['repas_id']) && isset($_POST['recette'])) {
            $_POST['nom'] = htmlspecialchars($_POST['nom']);
            $_POST['repas_id'] = htmlspecialchars($_POST['repas_id']);
            $user = $security->getUser();
            if (empty($_POST['image'])) {
                $_POST['image'] = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
            } else {
                $_POST['image'] = htmlspecialchars($_POST['image']);
            }
            if ($_POST['repas_id'] == "new") {
                $sqlRepas = new Repas();
                $sqlRepas->setNom($_POST['nom'])
                    ->setImage($_POST['image'])
                    ->setRecette($_POST['recette'])
                    ->setPostedBy($user->getUsername())
                    ->setCreatedAt(new \DateTime());
                $entityManager->persist($sqlRepas);
                $entityManager->flush();
                $errors = $validator->validate($sqlRepas);
                $id = $sqlRepas->getId();
                $success = "Le repas à bien été créer !";
            } else {
                $repas = $entityManager->getRepository(Repas::class)->find($_POST['repas_id']);
                $repas->setNom($_POST['nom'])
                    ->setRecette($_POST['recette'])
                    ->setImage($_POST['image']);
                $entityManager->flush();
                $errors = $validator->validate($repas);
                $id = $_POST['repas_id'];
                $success = "Le repas à bien été mis à jour !";
            }
            if (!empty($_POST['nom']) && !empty($_POST['repas_id'])) {
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

    /**
     * @Route("/admin/uploadImage", name="admin_upload_image")
     */
    public function uploadImage(): Response
    {
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
}
