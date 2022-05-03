<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class LodgingFunctionalTest extends ApiTestCase
{
  public function testGetCollection(): void
  {
    $response = static::createClient()->request('GET', 'api/lodgings');

    $this->assertResponseIsSuccessful();

    $this->assertResponseHeaderSame(
      'content-type',
      'application/ld+json; charset=utf-8'
    );

    $this->assertJsonContains([
      "@context" => "/api/contexts/Lodging",
      "@id" => "/api/lodgings",
      "@type" => "hydra:Collection",
      "hydra:totalItems" => 20,
      'hydra:view' => [
        '@id' => '/api/lodgings?page=1',
        '@type' => 'hydra:PartialCollectionView',
        'hydra:first' => '/api/lodgings?page=1',
        'hydra:last' => '/api/lodgings?page=2',
        'hydra:next' => '/api/lodgings?page=2',
      ],
    ]);

    $this->assertCount(10, $response->toArray()['hydra:member']);
  }

  public function testPagination(): void
  {
    $response = static::createClient()->request('GET', 'api/lodgings?page=2');

    $this->assertJsonContains([
      "@context" => "/api/contexts/Lodging",
      "@id" => "/api/lodgings",
      "@type" => "hydra:Collection",
      "hydra:totalItems" => 20,
      'hydra:view' => [
        '@id' => '/api/lodgings?page=2',
        '@type' => 'hydra:PartialCollectionView',
        'hydra:first' => '/api/lodgings?page=1',
        'hydra:last' => '/api/lodgings?page=2',
        'hydra:previous' => '/api/lodgings?page=1',
      ],
    ]);
  }

  public function testCreateLodging(): void
  {
    static::createClient()->request('POST', '/api/lodgings', [
      'json' => [
        "name" => "testName",
        "rate" => 333,
        "description" => "This is the description for a test lodging that is here to fill the description to over 50 chars.",
        "address" => "City, Region",
        "owner" => "api/owners/13",
        "category" => "api/categories/26",
      ]
    ]);

    $this->assertResponseStatusCodeSame(201);

    $this->assertResponseHeaderSame(
      'content-type',
      'application/ld+json; charset=utf-8'
    );

    $this->assertJsonContains([
      "name" => "testName",
      "rate" => 333,
      "description" => "This is the description for a test lodging that is here to fill the description to over 50 chars.",
      "address" => "City, Region",
      "owner" => "/api/owners/13",
      "category" => array (
        '@id' => '/api/categories/26',
        '@type' => 'Category',
        'type' => 'cabane sur l\'eau',
      ),
    ]);
  }

  public function testUpdateLodging(): void
  {
    $client = static::createClient();

    $client->request('PATCH', '/api/lodgings/21', [
      "headers" => [
        "content-type" => "application/merge-patch+json; charset=utf-8"
      ],
      "json" => [
        'description' => 'An updated description for this lodging that is at least 50 chars long'
      ]
  ]);

    $this->assertResponseIsSuccessful();

    $this->assertResponseHeaderSame(
      'content-type',
      'application/ld+json; charset=utf-8'
    );

    $this->assertJsonContains([
      '@id' => '/api/lodgings/21',
      'description' => 'An updated description for this lodging that is at least 50 chars long',
    ]);
  }
}
