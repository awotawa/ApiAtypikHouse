<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Owner;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OwnerTest extends KernelTestCase
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
  }

  public function testValidOwner(): void
  {
    $this->assertHasErrors($this->owner, 0);
  }

  //getId
  public function testGetIdOwner()
  {
    $this->assertSame(null, $this->owner->getId());
  }

  //getUserId
  public function testGetUserIdOwner()
  {
    $this->assertSame($this->user, $this->owner->getUser());
  }
}
