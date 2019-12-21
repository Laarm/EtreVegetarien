<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Store;

class StoreFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1; $i <= 10; $i++) {
            $store = new Store();
            $store->setNom("Store n°$i")
                  ->setLocation("78 avenue de Paris")
                  ->setAdresse("78 avenue de Paris")
                  ->setImage("http://placehold.it/350x250")
                  ->setVille("Limoges")
                  ->setCreatedAt(new \DateTime());

            $manager->persist($store);
        }

        $manager->flush();
    }
}
