<?php

namespace App\Controller\AdminEdit;

use App\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/admin/deleteContact", name="admin_delete_contact")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return Response
     */
    public function deleteContact(Request $request, ValidatorInterface $validator): Response
    {
        if ($this->isCsrfTokenValid('delete-contact', $request->get('csrfData')) && !empty($request->get('id'))) {
            $sql = $this->getDoctrine()->getRepository(Contact::class)->find($request->get('id'));
            if (count($validator->validate($sql)) == 0) {
                $this->getDoctrine()->getRepository(Contact::class)->deleteContact($sql);
                return $this->json(['message' => "Vous avez bien supprimer ce message", 'id' => $request->get('id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
