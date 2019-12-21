<?php

namespace App\Repository;

use App\Entity\Store;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Store|null find($id, $lockMode = null, $lockVersion = null)
 * @method Store|null findOneBy(array $criteria, array $orderBy = null)
 * @method Store[]    findAll()
 * @method Store[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Store::class);
        $this->validator = $validator;
    }
    public function createStore($name, $image, $location, $address, $city)
    {
        $sqlStore = new Store();
        $sqlStore->setName($name)
            ->setImage($image)
            ->setLocation($location)
            ->setAddress($address)
            ->setCity($city)
            ->setCreatedAt(new \DateTime());
        $this->_em->persist($sqlStore);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlStore);
        if (count($errors) == 0) {
            return $sqlStore->getId();
        } else {
            return false;
        }
        return $sqlStore->getId();
    }
    public function saveStore($storeId, $name, $image, $address, $city)
    {
        $sqlStore = $this->find($storeId);
        $sqlStore->setName($name)
            ->setImage($image)
            ->setAddress($address)
            ->setCity($city);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlStore);
        if (count($errors) == 0) {
            return $storeId;
        } else {
            return false;
        }
    }
    public function deleteStore($storeId)
    {
        $sqlStore = $this->find($storeId);
        $this->_em->remove($sqlStore);
        $this->_em->flush();
        $errors = $this->validator->validate($sqlStore);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function searchStore($search, $limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->where('m.name LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
    public function getAllStore($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.name', 'm.image')
            ->orderBy('m.name', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
