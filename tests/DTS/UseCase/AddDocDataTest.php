<?php

namespace HomeCEU\Tests\DTS\UseCase;

use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\InMemory\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\UseCase\AddDocData;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class AddDocDataTest extends TestCase {
  const ENTITY_TYPE = 'person';

  /** @var  Persistence */
  private $persistence;

  /** @var  DocDataRepository */
  private $repository;

  public function setUp(): void {
    parent::setUp();
    $this->persistence = new DocDataPersistence();
    $this->repository = new DocDataRepository($this->persistence);
  }

  public function testCanAddEntity() {
    $fake = Faker::generator();
    $inputs = [
        'entityType' => self::ENTITY_TYPE,
        'entityKey' => $fake->md5,
        'data' => $this->profileData()
    ];
    $uc = new AddDocData($this->repository);
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