<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class BookFixture extends Fixture
{
	private $faker;

	public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
		$this->faker = Factory::create();
		for ($i= 0; $i <40; $i++) {
			$book = new Book();
			$book->setName($this->faker->text(15));
			$book->setDescription($this->faker->text(35));
			$book->setPublishYear($this->faker->date("Y"));
			$book->setCover($this->faker->imageUrl());
			$this->addReference("book ".$i, $book);
			$manager->persist($book);
		}
			$manager->flush();
    }
}
