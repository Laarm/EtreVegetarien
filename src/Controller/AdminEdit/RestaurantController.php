<?php

namespace App\Controller\AdminEdit;

use App\Entity\Restaurant;
use Exception;
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
     * @Route("/admin/deleteRestaurant/{id}", name="admin_delete_restaurant")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param Restaurant $restaurant
     * @return Response
     */
    public function deleteRestaurant(Request $request, Filesystem $filesystem, Restaurant $restaurant): Response
    {
        if ($this->isCsrfTokenValid('delete-restaurant', $request->get('csrfData'))) {
            $filesystem->remove(['symlink', "../public/" . $restaurant->getImage()]);
            $this->getDoctrine()->getRepository(Restaurant::class)->deleteRestaurant($restaurant);
            return $this->json(['message' => "Vous avez bien supprimer ce restaurant", 'id' => $restaurant->getId()], 200);
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/saveRestaurant", name="admin_save_restaurant")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     * @throws Exception
     */
    public function saveRestaurant(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('save-item', $request->get('csrfData'))) {
            $restaurant = new Restaurant();
            $restaurant->setCreatedAt(new \DateTime());
            if ($request->get('restaurant_id') !== "new") {
                $restaurant = $this->getDoctrine()->getRepository(Restaurant::class)->find($request->get('restaurant_id'));
                deleteFile($request->get('image'), $restaurant->getImage());
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
