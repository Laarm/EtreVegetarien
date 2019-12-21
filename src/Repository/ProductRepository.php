<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createProduct($nom, $image)
    {
        $sqlProduct = new Product();
        $sqlProduct->setNom($nom)
            ->setImage($image)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProduct);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduct);
        if (count($errors) == 0) {
            return $sqlProduct->getId();
        } else {
            return false;
        }
    }
    public function saveProduct($productId, $nom, $image)
    {
        $sqlProduct = $this->find($productId);
        $sqlProduct->setNom($nom)
            ->setImage($image);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduct);
        if (count($errors) == 0) {
            return $productId;
        } else {
            return false;
        }
    }
    public function deleteProduct($productId)
    {
        $sqlProduct = $this->find($productId);
        $this->entityManager->remove($sqlProduct);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduct);
        if (count($errors) == 0) {
            return true;
        } else {
            return false;
        }
    }
    public function searchProduct($search, $limit)
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
    public function getAllProduct($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
