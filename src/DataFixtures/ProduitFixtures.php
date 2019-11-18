<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Produit;

class ProduitFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 1; $i <= 10; $i++) {
            $produit = new Produit();
            $produit->setNom("Produit n°$i")
                    ->setImage("http://placehold.it/350x350")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($produit);
        }

        $manager->flush();
    }
}
