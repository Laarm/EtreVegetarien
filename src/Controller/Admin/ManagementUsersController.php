<?php namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ManagementUsersController extends AbstractController {

    public function users(UserRepository $managementUserRepo, Request $request) {
        $users=$managementUserRepo->findBy(array(), null, $request->get('maxView', 100), $view=$request->get('view', ""));
        return $this->render('admin/users.html.twig', [ 'users'=> $users,
        ]);
    }
}