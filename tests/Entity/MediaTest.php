<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Owner;
use App\Entity\Category;
use App\Entity\Lodging;
use App\Entity\Media;
use App\Tests\Entity\tools\AssertEntityTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MediaTest extends KernelTestCase
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

    $this->media = (new Media())
      ->setLink("https://unsplash.com/photos/Yd59eQJVYAo")
      ->setType("Photo")
      ->setLodging($this->lodging);
  }

  public function testValidMedia(): void
  {
    $this->assertHasErrors($this->media, 0);
  }

  //getId
  public function testGetIdMedia()
  {
    $this->assertSame(null, $this->media->getId());
  }

  //getLink
  public function testGetLinkMedia()
  {
    $this->assertSame("https://unsplash.com/photos/Yd59eQJVYAo", $this->media->getLink());
  }

  //getMediaType
  public function testGetMediaTypeMedia()
  {
    $this->assertSame("Photo", $this->media->getType());
  }

  //getLodgingId
  public function testGetLodgingIdMedia()
  {
    $this->assertSame($this->lodging, $this->media->getLodging());
  }
}
