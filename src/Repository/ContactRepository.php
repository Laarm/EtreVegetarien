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
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Contact::class);
        $this->validator = $validator;
    }
    public function sendMessage($name, $email, $subject, $message)
    {
        $sql = new Contact();
        $sql->setName($name)
            ->setEmail($email)
            ->setSubject($subject)
            ->setMessage($message)
            ->setCreatedAt(new \DateTime());
        $this->_em->persist($sql);
        $this->_em->flush();
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
        $this->_em->remove($sqlContact);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlContact);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
