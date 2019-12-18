<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        parent::__construct($registry, Article::class);
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }
    public function createArticle($nom, $contenu, $image)
    {
        $sqlArticle = new Article();
        $sqlArticle->setNom($nom)
            ->setContenu($contenu)
            ->setImage($image)
            ->setCreatedAt(new \DateTime());
        $this->entityManager->persist($sqlArticle);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlArticle);
        if (count($errors) == 0) {
            return $sqlArticle->getId();
        } else {
            return "not good";
        }
    }
    public function saveArticle($articleId, $nom, $contenu, $image)
    {
        $sqlArticle = $this->find($articleId);
        $sqlArticle->setNom($nom)
            ->setContenu($contenu)
            ->setImage($image);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlArticle);
        if (count($errors) == 0) {
            return $articleId;
        } else {
            return "not good";
        }
    }
    public function deleteArticle($articleId)
    {
        $sqlArticle = $this->find($articleId);
        $this->entityManager->remove($sqlArticle);
        $this->entityManager->flush();
        $errors = $this->validator->validate($sqlArticle);
        if (count($errors) == 0) {
            return "good";
        } else {
            return "not good";
        }
    }
}
