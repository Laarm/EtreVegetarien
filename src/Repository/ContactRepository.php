<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
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
    public function sendMessage($sql)
    {
        $this->_em->persist($sql);
        $this->_em->flush();
    }
    public function deleteContact($sql)
    {
        $this->_em->remove($sql);
        $this->_em->flush();
    }
}
