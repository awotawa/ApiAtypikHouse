<?php

namespace App\DataFixtures;

use App\Entity\Owner;
use DateTime;
use DateTimeInterface;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class OwnerFixtures extends Fixture implements DependentFixtureInterface
{

  public function getDependencies()
  {
    return [
      UserFixtures::class,
    ];
  }

  public function load(ObjectManager $manager)
  {

    for ($i = 0; $i < 5; $i++) {

      $owner = new Owner();
      $owner->setUser($this->getReference("USER" . $i));
      $manager->persist($owner);
      $this->addReference("OWNER" . $i, $owner);
    }


    $manager->flush();
  }
}
