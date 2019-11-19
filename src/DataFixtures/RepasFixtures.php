<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Repas;

class RepasFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        for($i = 1; $i <= 10; $i++) {
            $repas = new Repas();
            $repas->setNom("Repas n°$i")
                  ->setRecette("<p>Recette : Fromage</p>")
                  ->setImage("http://placehold.it/350x250")
                  ->setPostedBy("Admin")
                  ->setCreatedAt(new \DateTime());

            $manager->persist($repas);
        }

        $manager->flush();
    }
}
