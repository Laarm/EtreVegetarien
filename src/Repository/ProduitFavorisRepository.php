<?php

namespace App\Repository;

use App\Entity\Produit;
use App\Entity\ProduitFavoris;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method ProduitFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitFavoris[]    findAll()
 * @method ProduitFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, ProduitFavoris::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }
    public function getAllProduitsAvisForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedById = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
    public function addProduitFavoris($produitId)
    {
        $user = $this->security->getUser();
        $produit = $this->entityManager
            ->getRepository(Produit::class)
            ->find($produitId);
        $sqlProduitFavoris = new ProduitFavoris();
        $sqlProduitFavoris->setProduitId($produit)
            ->setPostedById($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlProduitFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlProduitFavoris);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function removeProduitFavoris($produitId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedById = ' . $userId)
            ->andwhere('r.produitId = ' . $produitId)
            ->getQuery();
        $produitFavorisId = $result->getResult();
        $deleteFavoris = $this->find($produitFavorisId[0]['id']);
        $this->entityManager->remove($deleteFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($deleteFavoris);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
}
