<?php

namespace App\Controller\AdminEdit;

use App\Entity\RestaurantFeedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantFeedbackController extends AbstractController
{
    /**
     * @Route("/admin/deleteRestaurantFeedback/{id}", name="admin_delete_restaurantfeedback")
     * @param Request $request
     * @param RestaurantFeedback $restaurantFeedback
     * @return Response
     */
    public function deleteRestaurantFeedback(Request $request, RestaurantFeedback $restaurantFeedback): Response
    {
        if ($this->isCsrfTokenValid('delete-restaurantfeedback', $request->get('csrfData'))) {
            $this->getDoctrine()->getRepository(RestaurantFeedback::class)->deleteRestaurantFeedback($restaurantFeedback);
            return $this->json(['message' => "Vous avez bien supprimer cet avis", 'id' => $restaurantFeedback->getId()], 200);
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
