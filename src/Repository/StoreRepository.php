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
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Store::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createStore($nom, $image, $location, $adresse, $ville)
    {
        $sqlStore = new Store();
        $sqlStore->setNom($nom)
            ->setImage($image)
            ->setLocation($location)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlStore);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlStore);
        if (count($errors) == 0) {
            return $sqlStore->getId();
        } else {
            return false;
        }
        return $sqlStore->getId();
    }
    public function saveStore($storeId, $nom, $image, $adresse, $ville)
    {
        $sqlStore = $this->find($storeId);
        $sqlStore->setNom($nom)
            ->setImage($image)
            ->setAdresse($adresse)
            ->setVille($ville);
        $this->entityManager->flush();
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
        $this->entityManager->remove($sqlStore);
        $this->entityManager->flush();
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
            ->select('m.id', 'm.nom', 'm.image')
            ->where('m.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
    public function getAllStore($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}