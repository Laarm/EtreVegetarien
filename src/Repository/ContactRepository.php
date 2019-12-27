<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }
    public function sendMessage($contact)
    {
        $this->_em->persist($contact);
        $this->_em->flush();
    }
    public function deleteContact($contact)
    {
        $this->_em->remove($contact);
        $this->_em->flush();
    }
}
