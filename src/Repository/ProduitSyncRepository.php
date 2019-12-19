<?php

namespace App\Repository;

use App\Entity\Magasin;
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
    public function getProduitOfMagasin($magasin)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.Magasin = :magasinId')
            ->setParameter('magasinId', $magasin)
            ->orderBy('m.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function deleteProduitMagasin($magasinId)
    {
        $sqlMagasin = $this->find($magasinId);
        $this->entityManager->remove($sqlMagasin);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMagasin);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function createProduitMagasin($magasinId, $produitId)
    {
        $magasinId = $this->entityManager->getRepository(Magasin::class)->find($magasinId);
        $produitId = $this->entityManager->getRepository(Produit::class)->find($produitId);
        $sqlProduit = new ProduitSync();
        $sqlProduit->setMagasin($magasinId)
            ->setProduit($produitId)
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
}
