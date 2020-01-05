<?php

namespace App\Controller\AdminEdit;

use App\Entity\RestaurantFeedback;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantFeedbackController extends AbstractController
{
    /**
     * @Route("/admin/deleteRestaurantFeedback", name="admin_delete_restaurantfeedback")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteRestaurantFeedback(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-restaurantfeedback', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(RestaurantFeedback::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $this->getDoctrine()->getRepository(RestaurantFeedback::class)->deleteRestaurantFeedback($sql);
                return $this->json(['message' => "Vous avez bien supprimer cet avis", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
