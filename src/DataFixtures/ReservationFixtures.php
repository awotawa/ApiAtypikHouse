<?php

namespace App\DataFixtures;

use App\Entity\Reservation;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{

  public function getDependencies()
  {
    return [
      UserFixtures::class,
      LodgingFixtures::class,
    ];
  }

  public function load(ObjectManager $manager)
  {
    $faker = Faker\Factory::create("fr_FR");

    for ($i = 0; $i < 10; $i++) {

      $reservation = new Reservation();
      $reservation->setUser($this->getReference("USER" . mt_rand(1, 9)));
      $reservation->setLodging($this->getReference("LODGING" . mt_rand(0, 19)));
      $reservation->setStartDate($faker->dateTime());
      $reservation->setEndDate($faker->dateTime());
      $reservation->setPaid($faker->boolean(50));
      $manager->persist($reservation);
    }

    $manager->flush();
  }
}
