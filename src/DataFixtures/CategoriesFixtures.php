<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use DateTimeImmutable;
use Symfony\Component\HttpClient\CurlHttpClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoriesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new CurlHttpClient();
        $response = $client->request(
            'GET',
            'https://fakestoreapi.com/products/categories'
        );
        $categories = json_decode($response->getContent());

        foreach($categories as $categorie){
            $newCategorie = new Categories();
            $newCategorie->setName($categorie);
            $newCategorie->setCreatedAt(new DateTimeImmutable());
            $manager->persist($newCategorie);
        }

        $manager->flush();
    }
}
