<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, User::class);
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    public function saveUserProfil($userId, $username, $email, $role, $bio, $preference, $preferenceCreatedAt)
    {
        $sqlUser = $this->find($userId);
        $sqlUser->setUsername($username)
            ->setEmail($email)
            ->setRole($role)
            ->setPreference($preference)
            ->setPreferenceCreatedAt(new \DateTime($preferenceCreatedAt))
            ->setBio($bio);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlUser);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function saveUserAvatar($userId, $avatar)
    {
        $sqlUser = $this->find($userId);
        $sqlUser->setAvatar($avatar);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlUser);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function saveUserPassword($userId, $password)
    {
        $sqlUser = new User();
        $password = $this->passwordEncoder->encodePassword($sqlUser, $password);
        $sqlUser = $this->find($userId);
        $sqlUser->setPassword($password);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlUser);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteUser($userId)
    {
        $sqlUser = $this->find($userId);
        $this->entityManager->remove($sqlUser);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlUser);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function createUser($username, $password, $email)
    {
        $sqlUser = new User();
        $password = $this->passwordEncoder->encodePassword($sqlUser, $password);
        $sqlUser->setUsername($username)
            ->setPassword($password)
            ->setEmail($email)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlUser);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlUser);
        $return = array("password" => $password, "id" => $sqlUser->getId());
        if (count($errors) == 0) {
            return $return;
        } else {
            return false;
        }
    }
}
