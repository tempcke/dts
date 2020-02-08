<?php


namespace HomeCEU\Tests\DTS\Repository;

use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\InMemory\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\Tests\Faker;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

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

  public function testDocDataHistory() {
    $a1 = $this->fakeDocData('A');
    $a2 = $this->fakeDocData('A');
    $b1 = $this->fakeDocData('B');
    $this->repo->save($a1);
    $this->repo->save($b1);
    $this->repo->save($a2);
    $versions = $this->repo->allVersions('A');
    Assert::assertSame(2, count($versions));
    $fetchedIds = [];
    foreach ($versions as $v) {
      var_dump($v);
      Assert::assertSame('A', $v['dataKey']);
      array_push($fetchedIds, $v['dataId']);
    }
    Assert::assertContains($a1->dataId, $fetchedIds);
    Assert::assertContains($a2->dataId, $fetchedIds);
  }

  protected function fakeDocData($key=null) {
    if (is_null($key)) $key = uniqid();
    $type = self::ENTITY_TYPE;
    $data = ['hash'=>Faker::generator()->md5];
    return $this->repo->newDocData($type, $key, $data);
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