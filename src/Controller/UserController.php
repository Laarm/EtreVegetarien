<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user")
     */
    public function index(int $accountId, EntityManagerInterface $entityManager): Response
    {
        $bdd = $this->getDoctrine()->getEntityManager()->getConnection();
        $sql = '
            SELECT * FROM user_params
            WHERE account_id = :account_id
            AND param_id = :param_id
            ';
        $stmt = $bdd->prepare($sql);
        $stmt->execute([
            'account_id' => $accountId,
            'param_id' => "username"
            ]);
        $stmt = $stmt->fetchAll();
        return $this->render('user/index.html.twig', [
            'username' => $stmt[0]['info'],
        ]);
    }
}