<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Acteur;
use App\Entity\Realisateur;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class PersonnelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        // créer list des acteurs
        for ($i = 0; $i<50; $i++) {
            $acteur = new Acteur;
            $acteur->setPrenom($faker->firstName);
            $acteur->setNom($faker->lastName);
            $manager->persist($acteur);
        }

        // créer list des Realisateur
        for ($i = 0; $i<5; $i++) {
            $realisateur = new Realisateur;
            $realisateur->setPrenom($faker->firstName);
            $realisateur->setNom($faker->lastName);
            $manager->persist($realisateur);
        }

        $manager->flush();
    }
}
