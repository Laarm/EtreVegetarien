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
                    ->setImage("https://s3.croquonslavie.fr/cdn/farfuture/mxPjZ5RXQUDMLNPRP5Mqg7yWC0Ty--moWQJtSEmkzOc/mtime:1561614859/sites/default/files/images/product/principal/fa4e28e3-2cd0-44db-9563-7e2bfb651465.png")
                    ->setCreatedAt(new \DateTime());

            $manager->persist($produit);
        }

        $manager->flush();
    }
}
