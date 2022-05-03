<?php

namespace App\Tests;

use DateTime;
use App\Entity\User;
use App\Entity\Owner;
use App\Entity\Lodging;
use App\Entity\Category;
use App\Entity\Reservation;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReservationTest extends KernelTestCase
{
  use AssertEntityTrait;

  //This will launch before every tests
  public function setUp(): void
  {

    $this->user = (new User())
      ->setFirstName("John")
      ->setLastName("Smith")
      ->setPhoto("https://randomuser.me/api/portraits/men/66.jpg")
      ->setEmail("john.smith@yopmail.com")
      ->setPassword("azertyuiop");
    // ->setIsVerified(true)

    $this->owner = (new Owner())
      ->setUser($this->user);

    $this->category = (new Category())
      ->setType("Châlet");

    $this->lodging = (new Lodging())
      ->setOwner($this->owner)
      ->setRate(200)
      ->setDescription("Petit châlet cosy au sein des Alpes. Vous êtes à deux pas des stations Pouillot-les-Bains et Gigot-Crue. Profitez du grand air de la montagne et passez des moments conviviaux avec ce petit châlet tranquille et rustique.")
      ->setName("Châlet Albert")
      ->setAddress("Annecy, Auvergne-Rhône-Alpe")
      ->setCategory($this->category);

    $this->reservation = (new Reservation())
      ->setUser($this->user)
      ->setLodging($this->lodging)
      ->setStartDate(new DateTime('03/13/2022'))
      ->setEndDate(new DateTime('03/16/2022'))
      ->setPaid(true);
  }

  public function testValidReservation(): void
  {
    $this->assertHasErrors($this->reservation, 0);
  }

  //getId
  public function testGetIdReservation()
  {
    $this->assertSame(null, $this->reservation->getId());
  }

  //getStartDate
  //setStartDate
  public function testGetStartDateReservation()
  {
    $this->assertEquals(new DateTime('03/13/2022'), $this->reservation->getStartDate());
  }

  //getEndDate
  public function testGetEndDateReservation()
  {
    $this->assertEquals(new DateTime('03/16/2022'), $this->reservation->getEndDate());
  }

  //setEndDate
  //getPaid
  public function testGetPaidReservation()
  {
    $this->assertSame(true, $this->reservation->getPaid());
  }

  //getUserId
  public function testGetUserIdReservation()
  {
    $this->assertSame($this->user, $this->reservation->getUser());
  }

  //setUserId
  //getLodgingId
  public function testGetLodgingIdReservation()
  {
    $this->assertSame($this->lodging, $this->reservation->getLodging());
  }

  //setLodgingId

}
