<?php

namespace App\DataFixtures;

use App\Entity\Categories;
use App\Entity\Products;
use App\Repository\CategoriesRepository;
use DateTimeImmutable;
use Symfony\Component\HttpClient\CurlHttpClient;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ManagerRegistry;

class ProductsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $client = new CurlHttpClient();
        $categoriesRepository = $manager->getRepository(Categories::class);
        $response = $client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );
        $products = json_decode($response->getContent());

        foreach($products as $product){
            $newProduct = new Products();
            $newProduct->setName($product->title);
            $newProduct->setCategoryId($categoriesRepository->getByName($product->category));
            $newProduct->setCreatedAt(new DateTimeImmutable());
            $newProduct->setStock(0);
            $manager->persist($newProduct);
        }

        $manager->flush();
    }
}
