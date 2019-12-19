<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Produit::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createProduit($nom, $image)
    {
        $sqlProduit = new Produit();
        $sqlProduit->setNom($nom)
            ->setImage($image)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProduit);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduit);
        if (count($errors) == 0) {
            return $sqlProduit->getId();
        } else {
            return "not good";
        }
    }
    public function saveProduit($produitId, $nom, $image)
    {
        $sqlProduit = $this->find($produitId);
        $sqlProduit->setNom($nom)
            ->setImage($image);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduit);
        if (count($errors) == 0) {
            return $produitId;
        } else {
            return "not good";
        }
    }
    public function deleteProduit($produitId)
    {
        $sqlProduit = $this->find($produitId);
        $this->entityManager->remove($sqlProduit);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduit);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function searchProduit($search, $limit)
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
    public function getAllProduit($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
