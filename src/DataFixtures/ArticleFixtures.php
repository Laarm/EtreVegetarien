<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\ArticleComment;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('Fr_fr');

        // for($i =1; $i <= 3; $i++) {
        //     $comment = new ArticleComment();
        // }
        for($i = 1; $i <= 10; $i++) {
            $article = new Article();
            $article->setNom($faker->sentence())
                    ->setContenu("<p>".$faker->paragraph()."</p>")
                    ->setImage("http://placehold.it/303x201")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($article);
        }

        $manager->flush();
    }
}
