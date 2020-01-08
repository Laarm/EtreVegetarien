<?php

namespace App\Controller\AdminEdit;

use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{

    /**
     * @Route("/admin/user/{id}/edit", name="admin_edit_user")
     * @param User $user
     * @return Response
     */
    public function editUser(User $user)
    {
        return $this->render('admin/edit/user.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/saveUser", name="admin_save_user")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param ValidatorInterface $validator
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function saveUser(Request $request, Filesystem $filesystem, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isCsrfTokenValid('save-user', $request->get('csrfData'))) {
            $sqlUser = $this->getDoctrine()->getRepository(User::class)->find($request->get('user_id'));
            $sqlUser->setUsername($request->get('username'))
                ->setEmail($request->get('email'))
                ->setRole($request->get('role'))
                ->setBio($request->get('bio'));
            if ($request->get('deleteAvatar') == "true") {
                if(!empty($sqlUser->getAvatar())){$filesystem->remove(['symlink', "../public/" . $sqlUser->getAvatar(), 'activity.log']);}
                $sqlUser->setAvatar(null);
            }
            if (!empty($request->get('motdepasse'))) {
                $sqlUser->setPassword($passwordEncoder->encodePassword($sqlUser, $request->get('motdepasse')));
            }
            if (count($validator->validate($sqlUser)) == 0) {
                $this->getDoctrine()->getRepository(User::class)->saveUserProfil($sqlUser);
                return $this->json(['message' => "L'utilisateur à bien été mis à jour !", 'userId' => $request->get('user_id')], 200);
            }
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }

    /**
     * @Route("/admin/deleteUser/{id}", name="admin_delete_user")
     * @param Request $request
     * @param Filesystem $filesystem
     * @param User $user
     * @return Response
     */
    public function deleteUser(Request $request, Filesystem $filesystem, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete-user', $request->get('csrfData'))) {
            if(!empty($user->getAvatar())){$filesystem->remove(['symlink', "../public/" . $user->getAvatar()]);}
            $this->getDoctrine()->getRepository(User::class)->deleteUser($user);
            return $this->json(['message' => "Vous avez bien supprimer cet utilisateur", 'id' => $user->getId()], 200);
        }
        return $this->json(['message' => 'Veuillez contacter un administrateur !'], 400);
    }
}
