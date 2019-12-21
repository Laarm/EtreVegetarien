<?php

namespace App\Repository;

use App\Entity\Store;
use App\Entity\Produit;
use App\Entity\ProduitSync;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProduitSync|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitSync|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitSync[]    findAll()
 * @method ProduitSync[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitSyncRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, ProduitSync::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function getProduitOfStore($store)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.Store = :storeId')
            ->setParameter('storeId', $store)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function deleteProduitStore($storeId)
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
    public function createProduitStore($storeId, $produitId)
    {
        $storeId = $this->entityManager->getRepository(Store::class)->find($storeId);
        $produitId = $this->entityManager->getRepository(Produit::class)->find($produitId);
        $sqlProduit = new ProduitSync();
        $sqlProduit->setStore($storeId)
            ->setProduit($produitId)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProduit);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduit);
        if (count($errors) == 0) {
            return $sqlProduit->getId();
        } else {
            return false;
        }
    }
}
