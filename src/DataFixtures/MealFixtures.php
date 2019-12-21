<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Meal;

class MealFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for ($i = 1; $i <= 10; $i++) {
            $meal = new Meal();
            $meal->setName("Meal n°$i")
                ->setRecipe("<p>Recipe : Fromage</p>")
                ->setImage("http://placehold.it/350x250")
                ->setPostedBy("Admin")
                ->setCreatedAt(new \DateTime());

            $manager->persist($meal);
        }

        $manager->flush();
    }
}
