<?php

namespace HomeCEU\Tests\DocumentCreator\UseCase;

use HomeCEU\DocumentCreator\Persistence;
use HomeCEU\DocumentCreator\Persistence\InMemory\EntityPersistence;
use HomeCEU\DocumentCreator\Repository\EntityRepository;
use HomeCEU\DocumentCreator\UseCase\AddEntity;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DocumentCreator\TestCase;
use PHPUnit\Framework\Assert;

class AddEntityTest extends TestCase {
  const ENTITY_TYPE = 'person';

  /** @var  Persistence */
  private $persistence;

  /** @var  EntityRepository */
  private $repository;

  public function setUp(): void {
    parent::setUp();
    $this->persistence = new EntityPersistence();
    $this->repository = new EntityRepository($this->persistence);
  }

  public function testCanAddEntity() {
    $fake = Faker::generator();
    $inputs = [
        'entityType' => self::ENTITY_TYPE,
        'entityKey' => $fake->md5,
        'data' => $this->profileData()
    ];
    $uc = new AddEntity($this->repository);
    $entity = $uc->add(
        $inputs['entityType'],
        $inputs['entityKey'],
        $inputs['data']
    );
    $savedEntity = $this->persistence->retrieve($entity['entityId']);
    $this->assertEquals($entity, $savedEntity);
  }

  protected function profileData() {
    $fake = Faker::generator();
    return [
      "firstName" => $fake->firstName,
      "lastName" => $fake->lastName,
      "address" => $fake->address,
      "email" => $fake->email
    ];
  }
}