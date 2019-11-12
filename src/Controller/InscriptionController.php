<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    public function index()
    {
        return $this->render('inscription.html.twig', [
            'user_first_name' => "test",
        ]);
    }
    public function inscriptionAction()
    {
        if(!empty(htmlspecialchars($_POST['username']))
            &&
            !empty(htmlspecialchars($_POST['email']))
            &&
            !empty(htmlspecialchars($_POST['passeword']))
        ){
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $passeword = htmlspecialchars($_POST['passeword']);
        }else{
            echo "Veuillez remplir tout les champs !";
        }
    }
}