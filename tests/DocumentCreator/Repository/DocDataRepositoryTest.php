<?php


namespace HomeCEU\Tests\DocumentCreator\Repository;

use HomeCEU\DocumentCreator\Persistence;
use HomeCEU\DocumentCreator\Persistence\InMemory\DocDataPersistence;
use HomeCEU\DocumentCreator\Repository\DocDataRepository;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DocumentCreator\TestCase;

class DocDataRepositoryTest extends TestCase {
  const ENTITY_TYPE = 'person';

  /** @var Persistence */
  protected $persistence;

  /** @var DocDataRepository */
  protected $repo;

  public function setUp(): void {
    parent::setUp();
    $this->persistence = $this->persistence();
    $this->repo = new DocDataRepository($this->persistence);
  }

  public function testNewEntity() {
    $fake = Faker::generator();
    $type = self::ENTITY_TYPE;
    $key = $fake->md5;
    $data = $this->profileData();
    $e = $this->repo->newEntity($type, $key, $data);
    $this->assertSame($type, $e->entityType);
    $this->assertSame($key, $e->entityKey);
    $this->assertSame($data, $e->data);
    $this->assertNotEmpty($e->entityId);;
    $this->assertNotEmpty($e->createdAt);
  }

  public function testSave() {
    $fake = Faker::generator();
    $type = self::ENTITY_TYPE;
    $key = $fake->md5;
    $data = $this->profileData();
    $entity = $this->repo->newEntity($type, $key, $data);
    $this->repo->save($entity);
    $savedEntity = $this->persistence->retrieve($entity->entityId);
    $this->assertEquals($entity->toArray(), $savedEntity);
  }

  protected function persistence() {
    return new DocDataPersistence();
  }

  protected function profileData() {
    $fake = Faker::generator();
    return [
        "firstName" => $fake->firstName,
        "lastName"  => $fake->lastName,
        "address"   => $fake->address,
        "email"     => $fake->email
    ];
  }
}