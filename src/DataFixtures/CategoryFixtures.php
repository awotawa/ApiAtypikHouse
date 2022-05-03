<?php

namespace App\DataFixtures;

use App\Entity\Category;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CategoryFixtures extends Fixture
{

  public function load(ObjectManager $manager)
  {

    $faker = Faker\Factory::create("fr_FR");

    for ($i = 0; $i < 20; $i++) {

      $category = new Category();
      $category->setType($faker->randomElement(["cabane", "cabane dans les arbres", "cabane sur l'eau", "cabane sur pilotis", "bulle", "yourte", "bateau", "dome", "tipi", "tiny house", "maison de hobbit", "chalet"]));
      $manager->persist($category);
      $this->addReference("CATEGORY" . $i, $category);
    }

    $manager->flush();
  }
}
