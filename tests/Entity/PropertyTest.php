<?php

namespace App\Tests;

use App\Entity\Property;
use App\Entity\Category;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PropertyTest extends KernelTestCase
{
  use AssertEntityTrait;

  //This will launch before every tests
  public function setUp(): void
  {

    $this->category = (new Category())
      ->setType("myType");

    $this->property = (new Property())
      ->setCategory($this->category)
      ->setNewField("Hauteur Sol")
      ->setDefaultValue("13 mètre");
  }

  public function testValidProperty(): void
  {
    $this->assertHasErrors($this->property, 0);
  }

  //ID TESTING
  // GET id
  public function testGetIdProperty()
  {
    $this->assertSame(null, $this->property->getId());
  }

  //FOREIGN ID TESTING
  // GET category_id
  public function testGetCategoryIdProperty()
  {
    $this->assertSame($this->category, $this->property->getCategory());
  }

  //PROPERTY TESTING
  // SET Too long new_field
  public function testNewFieldTooLongProperty(): void
  {
    $this->assertHasErrors($this->property->setNewField("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"), 1, 'newField', 'Your newField cannot be longer than 30 characters');
  }

  // Invalid characters new_field
  public function testNewFieldWrongCharacterProperty(): void
  {
    $this->assertHasErrors($this->property->setNewField("aaaaaaa>>"), 1, 'newField', 'This value is not valid.');
  }

  // GET new_field
  public function testGetNewFieldProperty()
  {
    $this->assertSame("Hauteur Sol", $this->property->getNewField());
  }

  // SET Too long default_value
  public function testDefaultValueTooLongProperty(): void
  {
    $this->assertHasErrors($this->property->setDefaultValue("aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa"), 1, 'defaultValue', 'Your defaultValue cannot be longer than 30 characters');
  }

  // Invalid characters new_field
  public function testDefaultValueWrongCharacterProperty(): void
  {
    $this->assertHasErrors($this->property->setDefaultValue("aaaaaaa>>"), 1, 'defaultValue', 'This value is not valid.');
  }

  // GET new_field
  public function testGetDefaultValueProperty()
  {
    $this->assertSame("13 mètre", $this->property->getDefaultValue());
  }

}
