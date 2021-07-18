<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AuthorFixture extends Fixture implements DependentFixtureInterface
{
	private $faker;
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
		$this->faker = Factory::create();
		for($i=0; $i < 40; $i++) {
			$author = new Author();
			$author->setName($this->faker->name());
			for($num_books = 0; $num_books < $this->faker->numberBetween(0,3); $num_books++) {
				$book = $this->getReference("book ".$this->faker->numberBetween(0, 39));
				$author->addBook($book);
			}
			$manager->persist($author);
		}
			$manager->flush();
    }
	public function getDependencies(): array
	{
		return [
			BookFixture::class,
		];
	}
}
