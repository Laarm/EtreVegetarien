<?php

namespace App\Controller\AdminEdit;

use App\Entity\Restaurant;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{

    /**
     * @Route("/admin/arestaurant/{id}/edit", name="admin_edit_restaurant")
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
        if ($this->isCsrfTokenValid('delete-restaurant', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $filesystem->remove(['symlink', "../public/" . $sql->getImage(), 'activity.log']);
                $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($sql);
                return $this->json(['message' => "Vous avez bien supprimer ce restaurant", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveRestaurant", name="admin_save_restaurant")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @return Response
     * @throws \Exception
     */
    public function saveRestaurant(Request $request, Filesystem $filesystem, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
//            $image = "https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103";
//            if (!empty($request->get('image'))) {$image = htmlspecialchars($request->get('image'));}
            $image = $request->get('image', 'https://scontent-cdg2-1.cdninstagram.com/vp/23a0f75b8f3f1f8d4324fd331f2526f0/5E5FF4E8/t51.2885-15/e35/s1080x1080/71022418_387653261929539_2767454389404154771_n.jpg?_nc_ht=scontent-cdg2-1.cdninstagram.com&_nc_cat=103');
            if ($request->get('restaurant_id') == "new") {
                $restaurant = new Restaurant();
                $restaurant->setName($request->get('name'))
                    ->setImage($image)
                    ->setLocation("null")
                    ->setAddress($request->get('address'))
                    ->setCity($request->get('city'))
                    ->setContent($request->get('content'))
                    ->setCreatedAt(new \DateTime());
                if (count($validator->validate($restaurant)) == 0) {
                    $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->createRestaurant($restaurant);
                    return $this->json(['message' => "Le restaurant à bien été créer !", 'restaurantId' => $restaurant], 200);
                }
            } else {
                $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                $oldImage = $restaurant->getImage();
                $restaurant->setName($request->get('name'))
                    ->setImage($image)
                    ->setAddress($request->get('address'))
                    ->setCity($request->get('city'))
                    ->setContent($request->get('content'));
                if (count($validator->validate($restaurant)) == 0) {
                    if (substr($oldImage, 0, 4) !== "http" && $request->get('image') !== $oldImage) {
                        $filesystem->remove(['symlink', "../public/" . $oldImage, 'activity.log']);
                    }
                    $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($restaurant);
                    return $this->json(['message' => "Le restaurant à bien été mis à jour !", 'restaurantId' => $restaurant], 200);
                }
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
