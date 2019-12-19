<?php

namespace App\Repository;

use App\Entity\Repas;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Repas|null find($id, $lockMode = null, $lockVersion = null)
 * @method Repas|null findOneBy(array $criteria, array $orderBy = null)
 * @method Repas[]    findAll()
 * @method Repas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Repas::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createRepas($nom, $image, $recette, $user)
    {
        $sqlRepas = new Repas();
        $sqlRepas->setNom($nom)
            ->setImage($image)
            ->setRecette($recette)
            ->setPostedBy($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlRepas);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRepas);
        if (count($errors) == 0) {
            return $sqlRepas->getId();
        } else {
            return "not good";
        }
    }
    public function saveRepas($repasId, $nom, $image, $recette, $user)
    {
        $sqlRepas = $this->find($repasId);
        $sqlRepas->setNom($nom)
            ->setImage($image)
            ->setRecette($recette)
            ->setPostedBy($user);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRepas);
        if (count($errors) == 0) {
            return $repasId;
        } else {
            return "not good";
        }
    }
    public function deleteRepas($repasId)
    {
        $sqlRepas = $this->find($repasId);
        $this->entityManager->remove($sqlRepas);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRepas);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function searchRepas($search)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->where('m.nom LIKE :search')
            ->setParameter('search', '%' . $search . '%')
            ->orderBy('m.nom', 'ASC')
            ->getQuery();
        return $result->getResult();
    }
    public function getAllRepas()
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->getQuery();
        return $result->getResult();
    }
}
