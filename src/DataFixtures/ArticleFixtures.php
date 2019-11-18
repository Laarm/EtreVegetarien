<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setNom("Title de l'article n°$i")
                    ->setContenu("<p>Salut je suis un article qui comporte le numero $i</p>")
                    ->setImage("http://placehold.it/303x201")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
