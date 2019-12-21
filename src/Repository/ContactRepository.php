<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Contact::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function sendMessage($nom, $email, $sujet, $message)
    {
        $sql = new Contact();
        $sql->setNom($nom)
            ->setEmail($email)
            ->setSujet($sujet)
            ->setMessage($message)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sql);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sql);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function deleteContact($contactId)
    {
        $sqlContact = $this->find($contactId);
        $this->entityManager->remove($sqlContact);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlContact);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
