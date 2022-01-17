<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Writer;
use App\Entity\Article;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $facker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName($facker->word);
            $category->setDescription($facker->text);
            
            $manager->persist($category);
        }

        for ($i = 0; $i < 10; $i++) {
            $writer = new Writer();
            $writer->setName($facker->lastname);
            $writer->setFirstname($facker->firstname);

            $manager->persist($writer);
        }

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article->setTitle($facker->word);
            $article->setContent($facker->text);
            $article->setPublished($facker->boolean);
            $article->setDate($facker->dateTime);

            $manager->persist($article);
        }
        $manager->flush();
    }
}
