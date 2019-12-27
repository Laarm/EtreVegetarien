<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function saveUserProfil($user)
    {
        $this->_em->flush();
    }
    public function saveUserAvatar($user)
    {
        $this->_em->flush();
    }
    public function saveUserPassword($user)
    {
        $this->_em->flush();
    }
    public function deleteUser($user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }
    public function createUser($sql)
    {
        $this->_em->persist($sql);
        $this->_em->flush();
    }
}
