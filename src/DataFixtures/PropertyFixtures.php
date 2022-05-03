<?php

namespace App\DataFixtures;

use App\Entity\Property;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class PropertyFixtures extends Fixture implements DependentFixtureInterface
{

  public function getDependencies()
  {
    return [
      CategoryFixtures::class,
    ];
  }

  public function load(ObjectManager $manager)
  {

    $faker = Faker\Factory::create("fr_FR");

    for ($i = 0; $i < 20; $i++) {

      $property = new Property();
      $property->setCategory($this->getReference("CATEGORY" . mt_rand(0, 19)));
      $property->setNewField($faker->randomElement(["Surface du logement ", "Nombre de pièces", "Nombre de chambres", "Hauteur Sol", "Eau", "Electricité", "Distance du parking"]));
      $property->setDefaultValue($faker->randomElement(["20m² ", "2", "1", "2m", "Eau courante", "oui", "5km"]));
      $manager->persist($property);
      $this->addReference("PROPERTY" . $i, $property);
    }

    $manager->flush();
  }
}
