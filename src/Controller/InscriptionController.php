<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserParams;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function index()
    {
        return $this->render('inscription.html.twig', [
            'user_first_name' => "test",
        ]);
    }
    public function inscriptionAction(ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        if(
            strlen($_POST['username']) > 5
            &&
            strlen($_POST['username']) < 20
            &&
            strlen($_POST['email']) > 5
            &&
            strlen($_POST['email']) < 70
            &&
            preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email'])
            &&
            strlen($_POST['password']) > 6
            &&
            strlen($_POST['password']) < 70
        ){
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $entityManager = $this->getDoctrine()->getManager();
            $sqlUser = new User();
            $account_id = mt_rand(54557,579964679);
            $sqlUser->setAccountId(strval($account_id));
            $sqlUser->setPassword($password);
            $sqlUser->setCreated(strval(time()));
            $entityManager->persist($sqlUser);
            $entityManager->flush();
            $errors = $validator->validate($sqlUser);
            if(count($errors) == 0){
                $sqlUser_params = new UserParams();
                $sqlUser_params->setParamId("username");
                $sqlUser_params->setInfo($username);
                $sqlUser_params->setCreated(strval(time()));
                $entityManager->persist($sqlUser_params);
                $entityManager->flush();
                $sqlUser_params = new UserParams();
                $sqlUser_params->setParamId("email");
                $sqlUser_params->setInfo($email);
                $sqlUser_params->setCreated(strval(time()));
                $entityManager->persist($sqlUser_params);
                $entityManager->flush();
                return new Response('Success,Vous êtes bien inscrit !,'.$account_id);
            }else{
                return new Response('Error,Problème au niveau du serveur, veuillez contacter un administrateur !');
            }
        }else{
            if(strlen($_POST['username']) > 5){
                return new Response('Error,Votre pseudonyme est trop court !');
            }
            if(strlen($_POST['username']) < 20){
                return new Response('Error,Votre pseudonyme est trop long !'.$_POST['username']);
            }
            if(strlen($_POST['email']) > 5){
                return new Response('Error,Votre email est trop court !');
            }
            if(strlen($_POST['email']) < 20){
                return new Response('Error,Votre email est trop long !');
            }
            if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email'])){
                return new Response('Error,Votre email n\'est pas conforme (exemple : exemple@exemple.fr) !');
            }
            if(strlen($_POST['password']) > 6){
                return new Response('Error,Votre mot de passe est trop court !');
            }
            if(strlen($_POST['password']) < 70){
                return new Response('Error,Votre mot de passe est trop long !');
            }
            return new Response('Error,Veuillez remplir tout les champs !');
        }
    }
}
