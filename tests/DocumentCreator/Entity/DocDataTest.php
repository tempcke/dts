<?php


namespace HomeCEU\Tests\DocumentCreator\Entity;


use HomeCEU\DocumentCreator\Entity\DocData;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DocumentCreator\TestCase;
use PHPUnit\Framework\Assert;

class DocDataTest extends TestCase {
  public function testBuildFromState() {
    $entityState = $this->fakeEntity();
    $entity = DocData::fromState($entityState);
    Assert::assertEquals($entityState, $entity->toArray());
  }

  protected function fakeEntity() {
    $fake = Faker::generator();
    return [
        'entityId'   => $fake->uuid,
        'entityType' => 'completion',
        'entityKey'  => $fake->md5,
        'createdAt'  => $fake->iso8601,
        'data'       => [
            "firstName" => $fake->firstName,
            "lastName"  => $fake->lastName,
            "address"   => $fake->address,
            "email"     => $fake->email
        ]
    ];
  }
}