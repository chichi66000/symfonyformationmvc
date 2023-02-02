<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class GenreFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $genres = array(
            "Comédie",
            "Drame",
            "Romance",
            "Action",
            "Thriller",
            "Horreur",
            "Science-fiction",
            "Fantastique",
            "Policier",
            "Western",
            "Animation", "Documentaion"
        );
        
        foreach ($genres as $data) {
            $genre = new Genre();
            $genre->setType($data);
            $manager->persist($genre);
        } 

        $manager->flush();
    }
    
}
