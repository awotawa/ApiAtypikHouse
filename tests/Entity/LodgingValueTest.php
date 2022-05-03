<?php

namespace App\Tests;

use App\Entity\LodgingValue;
use App\Entity\Property;
use App\Entity\Lodging;
use App\Entity\Category;
use App\Entity\Owner;
use App\Entity\User;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LodgingValueTest extends KernelTestCase
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
      ->setType("myType");

    $this->lodging = (new Lodging())
      ->setOwner($this->owner)
      ->setName("L'Éden")
      ->setRate(59.99)
      ->setDescription("Lorem ipsum dolor ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur ipsum dolor sit amet consectetur sit amet consectetur")
      ->setAddress("2 rue Verdun, Rosny-sous-Bois, 93110")
      ->setCategory($this->category);

    $this->property = (new Property())
      ->setCategory($this->category)
      ->setNewField("Hauteur Sol")
      ->setDefaultValue("13 mètre");

    $this->lodgingValue = (new LodgingValue())
      ->setProperty($this->property)
      ->setLodging($this->lodging)
      ->setValue("myValue");
  }

  public function testValidLodgingValue(): void
  {
    $this->assertHasErrors($this->lodgingValue, 0);
  }


  //ID TESTING
  // GET id
  public function testGetIdLodgingValue()
  {
    $this->assertSame(null, $this->lodgingValue->getId());
  }


  //FOREIGN ID TESTING
  // GET property_id
  public function testGetPropertyIdLodgingValue()
  {
    $this->assertSame($this->property, $this->lodgingValue->getProperty());
  }

  // GET lodging_id
  public function testGetLodgingIdLodgingValue()
  {
    $this->assertSame($this->lodging, $this->lodgingValue->getLodging());
  }


  //VALUE TESTING
  // SET Too long value
  public function testValueTooLongLodgingValue(): void
  {
    $this->assertHasErrors($this->lodgingValue->setValue("aaaaaaaaaaa"), 1, 'value', 'Your value cannot be longer than 10 characters');
  }

  // Invalid characters value
  public function testValueWrongCharacterLodgingValue(): void
  {
    $this->assertHasErrors($this->lodgingValue->setValue("aé a>>"), 1, 'value', 'This value is not valid.');
  }

  // GET value
  public function testGetValueLodgingValue()
  {
    $this->assertSame("myValue", $this->lodgingValue->getValue());
  }

}
