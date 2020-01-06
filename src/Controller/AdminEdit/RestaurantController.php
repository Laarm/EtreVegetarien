<?php

namespace App\Controller\AdminEdit;

use App\Entity\Restaurant;
use Config\Functions;
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
            $restaurant = new Restaurant();
            $restaurant->setCreatedAt(new \DateTime());
            if ($request->get('restaurant_id') !== "new") {
                $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                $functions = new Functions();
                $functions->deleteFile($request->get('image'), $restaurant->getImage(), $filesystem);
            }
            $restaurant->setName($request->get('name'))->setLocation("null")
                ->setImage($request->get('image'))
                ->setAddress($request->get('address'))
                ->setCity($request->get('city'))
                ->setContent($request->get('content'));
            if (count($validator->validate($restaurant)) == 0) {
                $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->saveRestaurant($restaurant);
                return $this->json(['message' => "Le restaurant à bien été mis à jour !", 'restaurantId' => $restaurant], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
