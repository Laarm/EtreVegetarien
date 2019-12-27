<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
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
    public function __construct(ManagerRegistry $registry, ValidatorInterface $validator)
    {
        parent::__construct($registry, Article::class);
        $this->validator = $validator;
    }
    public function createArticle($sqlArticle)
    {
        $this->_em->persist($sqlArticle);
        $this->_em->flush();
        return $sqlArticle->getId();
    }
    public function saveArticle($sqlArticle)
    {
        $this->_em->flush();
        return $sqlArticle;
    }
    public function deleteArticle($sqlArticle)
    {
        $this->_em->remove($sqlArticle);
        $this->_em->flush();
        return $sqlArticle;
    }
}
