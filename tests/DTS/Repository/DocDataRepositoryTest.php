<?php


namespace HomeCEU\Tests\DTS\Repository;

use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\InMemory\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DTS\TestCase;

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
    $e = $this->repo->newDocData($type, $key, $data);
    $this->assertSame($type, $e->docType);
    $this->assertSame($key, $e->dataKey);
    $this->assertSame($data, $e->data);
    $this->assertNotEmpty($e->dataId);;
    $this->assertNotEmpty($e->createdAt);
  }

  public function testSave() {
    $fake = Faker::generator();
    $type = self::ENTITY_TYPE;
    $key = $fake->md5;
    $data = $this->profileData();
    $entity = $this->repo->newDocData($type, $key, $data);
    $this->repo->save($entity);
    $savedEntity = $this->persistence->retrieve($entity->dataId);
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