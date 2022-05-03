<?php

namespace App\Tests;

use App\Entity\Lodging;
use App\Entity\Category;
use App\Entity\Owner;
use App\Entity\User;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LodginTest extends KernelTestCase
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
      ->setType("myType01");

    $this->lodging = (new Lodging())
      ->setOwner($this->owner)
      ->setName("L'Éden")
      ->setRate(59.99)
      ->setDescription("Lorem ipsum dolor ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur sit amet consectetur")
      ->setAddress("2 rue Verdun, Rosny-sous-Bois, 93110")
      ->setCategory($this->category);
  }

  public function testValidLodging(): void
  {
    $this->assertHasErrors($this->lodging, 0);
  }


  //ID TESTING
  // GET id
  public function testGetIdLodging()
  {
    $this->assertSame(null, $this->lodging->getId());
  }

  //FOREIGN ID TESTING
  // GET owner_id
  public function testGetOwnerLodging()
  {
    $this->assertSame($this->owner, $this->lodging->getOwner());
  }

  // GET category_id
  public function testGetCategoryLodging()
  {
    $this->assertSame($this->category, $this->lodging->getCategory());
  }

  //NAME TESTING
  // Blank name
  public function testNameBlankLodging(): void
  {
    $this->assertHasErrors($this->lodging->setName(""), 2, 'name', 'This value should not be blank.');
    $this->assertHasErrors($this->lodging->setName(""), 2, 'name', 'The name must be at least 2 characters long');
  }

  // Too short name
  public function testNameTooShortLodging(): void
  {
    $this->assertHasErrors($this->lodging->setName("b"), 1, 'name', 'The name must be at least 2 characters long');
  }


  // Too long name
  public function testNameTooLongLodging(): void
  {
    $this->assertHasErrors($this->lodging->setName("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb"), 1, 'name', 'The name cannot be longer than 50 characters');
  }

  // Invalid characters name
  public function testNameWrongCharacterLodging(): void
  {
    $this->assertHasErrors($this->lodging->setName("aaaaaaaaaaaaaaaaaaaaaaaaa>>"), 1, 'name', 'This value is not valid.');
  }

  // GET name
  public function testGetNameLodging()
  {
    $this->assertSame("L'Éden", $this->lodging->getName());
  }


  //RATE TESTING
  // SET Too short Rate ( Rate < 0.01 )
  public function testRateTooShorLodging(): void
  {
    $this->assertHasErrors($this->lodging->setRate(0), 1, 'rate', 'This value should be between 0.01 and 9999.99.');
  }

  // SET Too high Rate ( Rate > 9999.99 )
  public function testRateTooHighLodging(): void
  {
    $this->assertHasErrors($this->lodging->setRate(10000), 1, 'rate', 'This value should be between 0.01 and 9999.99.');
  }

  // SET Not valide décimal Rate ( Rate = 250.025465 )
  public function testRateNotValideDecimalLodging(): void
  {
    $this->assertHasErrors($this->lodging->setRate(10.000001), 1, 'rate', 'This value is not valid.');
  }

  // GET Rate
  public function testGetRateLodging()
  {
    $this->assertSame(59.99, $this->lodging->getRate());
  }

  //LODGIN_DESCRIPTION TESTING
  // Blank description
  public function testDescriptionBlankLodging(): void
  {
    $this->assertHasErrors($this->lodging->setDescription(""), 2, 'description', 'This value should not be blank.');
    $this->assertHasErrors($this->lodging->setDescription(""), 2, 'description', 'Your description must be at least 50 characters long');
  }

  // Too long description
  public function testDescriptionTooLongLodging(): void
  {
    $this->assertHasErrors($this->lodging->setDescription("bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbBBBBBB"), 1, 'description', 'Your description cannot be longer than 255 characters');
  }

  // Invalid characters description
  public function testDescriptionWrongCharacterLodging(): void
  {
    $this->assertHasErrors($this->lodging->setDescription("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa>>"), 1, 'description', 'This value is not valid.');
  }

  // GET description
  public function testGetDescriptionLodging()
  {
    $this->assertSame("Lorem ipsum dolor ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur sit amet consectetur", $this->lodging->getDescription());
  }

  //ADRESS TESTING
  // Blank adress
  public function testAddressBlankLodging(): void
  {
    $this->assertHasErrors($this->lodging->setAddress(""), 1, 'address', 'This value should not be blank.');
  }

  // Too long adress
  public function testAdressTooLongLodging(): void
  {
    $this->assertHasErrors($this->lodging->setAddress("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"), 1, 'address', 'Your adress cannot be longer than 50 characters');
  }

  // GET adress
  public function testGetAddressLodging()
  {
    $this->assertSame("2 rue Verdun, Rosny-sous-Bois, 93110", $this->lodging->getAddress());
  }
}
