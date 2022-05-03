<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class CategoryFunctionalTest extends ApiTestCase
{
  public function testGetCollection(): void
  {
    static::createClient()->request('GET', 'api/categories');

    $this->assertResponseIsSuccessful();

    $this->assertResponseHeaderSame(
      'content-type', 'application/ld+json; charset=utf-8'
    );

    $this->assertJsonContains([
        "@context" => "/api/contexts/Category",
        "@id" => "/api/categories",
        "@type" => "hydra:Collection",
        "hydra:totalItems" => 20,
    ]);
  }
}
