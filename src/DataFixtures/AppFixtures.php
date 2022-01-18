<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Image;
use App\Entity\Writer;
use App\Entity\Article;
use App\Entity\Category;
use App\Repository\WriterRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $categoryRepository;
    private $writerRepository;

    public function __construct(CategoryRepository $categoryRepository, WriterRepository $writerRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->writerRepository = $writerRepository;
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Factory::create('fr_FR');
        $faker->seed(16);

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();

            $category->setName($faker->word);
            $category->setDescription($faker->text);

            $manager->persist($category);
            $manager->flush();
        }

        for ($i = 0; $i < 10; $i++) {
            $writer = new Writer();

            $writer->setName($faker->lastname);
            $writer->setFirstname($faker->firstname);

            $manager->persist($writer);
            $manager->flush();
        }

        for ($i = 0; $i < 10; $i++) {
            $image = new Image();

            $image->setSrc($faker->lastname);
            $image->setTitle($faker->firstname);
            $image->setAlt($faker->firstname);

            $manager->persist($image);
            $manager->flush();
        }

        for ($i = 0; $i < 10; $i++) {
            $article = new Article();

            $id_category = rand(0, 10);
            $id_writer = rand(0, 10);

            $category = $this->categoryRepository->find($id_category);
            $writer = $this->writerRepository->find($id_writer);

            $article->setTitle($faker->words(3, true));
            $article->setContent($faker->text);
            $article->setPublished($faker->boolean);
            $article->setDate($faker->dateTime);
            $article->setCategory($category);
            $article->setWriter($writer);

            $manager->persist($article);
        }

        $manager->flush();
    }
}
