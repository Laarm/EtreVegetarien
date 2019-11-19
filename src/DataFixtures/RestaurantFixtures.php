<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Restaurant;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1; $i <= 10; $i++) {
            $restaurant = new Restaurant();
            $restaurant->setNom("Restaurant n°$i")
                       ->setImage("http://placehold.it/350x250")
                       ->setLocation("74 avenue de France")
                       ->setAdresse("74 avenue de France")
                       ->setVille("Limoges")
                       ->setCreatedAt(new \DateTime());

            $manager->persist($restaurant);
        }

        $manager->flush();
    }
}
