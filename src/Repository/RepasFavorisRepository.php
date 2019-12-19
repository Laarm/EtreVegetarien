<?php

namespace App\Repository;

use App\Entity\Repas;
use App\Entity\RepasFavoris;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method RepasFavoris|null find($id, $lockMode = null, $lockVersion = null)
 * @method RepasFavoris|null findOneBy(array $criteria, array $orderBy = null)
 * @method RepasFavoris[]    findAll()
 * @method RepasFavoris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RepasFavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator, Security $security)
    {
        parent::__construct($registry, RepasFavoris::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->security = $security;
    }
    public function addRepasFavoris($repasId)
    {
        $user = $this->security->getUser();
        $sqlRepasFavoris = new RepasFavoris();
        $repas = $this->entityManager
            ->getRepository(Repas::class)
            ->find($repasId);
        $sqlRepasFavoris->setRepas($repas)
            ->setPostedBy($user)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlRepasFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlRepasFavoris);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function removeRepasFavoris($repasId, $userId)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.id')
            ->where('r.postedBy = ' . $userId)
            ->andwhere('r.Repas = ' . $repasId)
            ->getQuery();
        $repasFavorisId = $result->getResult();
        $deleteFavoris = $this->find($repasFavorisId[0]['id']);
        $this->entityManager->remove($deleteFavoris);
        $this->entityManager->flush();
        $errors = $this->validator->validate($deleteFavoris);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
    public function getAllRepasAvisForUser($user)
    {
        $result = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.postedBy = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery();
        return $result->getResult();
    }
}
