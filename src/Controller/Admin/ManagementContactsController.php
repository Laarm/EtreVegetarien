<?php namespace App\Controller\Admin;

use App\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementContactsController extends AbstractController {
    public function contacts(ContactRepository $managementcontactsRepo, Request $request) {
        $contacts=$managementcontactsRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/contacts.html.twig', [ 'contacts'=> $contacts,
        ]);
    }
}