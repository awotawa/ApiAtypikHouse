<?php

namespace App\DataFixtures;

use App\Entity\LodgingValue;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class LodgingValueFixtures extends Fixture implements DependentFixtureInterface
{

  public function getDependencies()
  {
    return [
      PropertyFixtures::class,
      LodgingFixtures::class,
    ];
  }

  public function load(ObjectManager $manager)
  {

    $faker = Faker\Factory::create("fr_FR");

    for ($i = 0; $i < 20; $i++) {

      $lodgingValue = new LodgingValue();
      $lodgingValue->setProperty($this->getReference("PROPERTY" . mt_rand(0, 19)));
      $lodgingValue->setLodging($this->getReference("LODGING" . mt_rand(0, 19)));
      $lodgingValue->setValue($faker->randomElement(["a", "b", "c", "d", "e", "f", "g", "h", "i"]));
      $manager->persist($lodgingValue);
    }

    $manager->flush();
  }
}
