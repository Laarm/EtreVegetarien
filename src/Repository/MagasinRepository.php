<?php

namespace App\Repository;

use App\Entity\Magasin;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Magasin|null find($id, $lockMode = null, $lockVersion = null)
 * @method Magasin|null findOneBy(array $criteria, array $orderBy = null)
 * @method Magasin[]    findAll()
 * @method Magasin[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MagasinRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Magasin::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createMagasin($nom, $image, $location, $adresse, $ville)
    {
        $sqlMagasin = new Magasin();
        $sqlMagasin->setNom($nom)
            ->setImage($image)
            ->setLocation($location)
            ->setAdresse($adresse)
            ->setVille($ville)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlMagasin);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMagasin);
        if (count($errors) == 0) {
            return $sqlMagasin->getId();
        } else {
            return "not good";
        }
        return $sqlMagasin->getId();
    }
    public function saveMagasin($magasinId, $nom, $image, $adresse, $ville)
    {
        $sqlMagasin = $this->find($magasinId);
        $sqlMagasin->setNom($nom)
            ->setImage($image)
            ->setAdresse($adresse)
            ->setVille($ville);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlMagasin);
        if (count($errors) == 0) {
            return $magasinId;
        } else {
            return "not good";
        }
    }
    public function deleteMagasin($magasinId)
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
    public function searchMagasin($search, $limit)
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
    public function getAllMagasin($limit)
    {
        $result = $this->createQueryBuilder('m')
            ->select('m.id', 'm.nom', 'm.image')
            ->orderBy('m.nom', 'ASC')
            ->setMaxResults($limit)
            ->getQuery();
        return $result->getResult();
    }
}
