<?php

namespace App\DataFixtures;

use App\Entity\Media;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class MediaFixtures extends Fixture implements DependentFixtureInterface
{

  public function getDependencies()
  {
    return [
      LodgingFixtures::class,
    ];
  }

  public function load(ObjectManager $manager)
  {
    $faker = Faker\Factory::create("fr_FR");

    for ($i = 0; $i < 40; $i++) {

      $media1 = new Media();
      $media1->setLink($faker->url());
      $media1->setLodging($this->getReference("LODGING" . mt_rand(0, 19)));
      $media1->setMediaType($faker->randomElement(['video', 'image']));
      $manager->persist($media1);
    }


    $manager->flush();
  }
}
