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
    $persistence = $this->persistenceSpy();
    $repo = new DocDataRepository($persistence);
    $docType = 'd';
    $dataKey = 'k';
    $repo->allVersions($docType, $dataKey);
    Assert::assertEquals(['docType'=>$docType, 'dataKey'=>$dataKey], $persistence->spiedFindFilter);
    Assert::assertContains('dataId', $persistence->spiedFindCols);
    Assert::assertContains('docType', $persistence->spiedFindCols);
    Assert::assertContains('dataKey', $persistence->spiedFindCols);
    Assert::assertContains('createdAt', $persistence->spiedFindCols);
    Assert::assertNotContains('data', $persistence->spiedFindCols);
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
  
  protected function persistenceSpy() {
    $p = new class implements Persistence {
      
      public $spiedFindFilter;
      public $spiedFindCols;
      
      public $spiedRetrieveId;
      public $spiedRetrieveCols;
      
      public $spiedPersistData;

      public function generateId() {}

      public function persist($data) {
        $this->spiedPersistData = $data;
      }

      public function retrieve($id, array $cols = ['*']) {
        $this->spiedRetrieveId = $id;
        $this->spiedRetrieveCols = $cols;
      }

      public function find(array $filter, array $cols = ['*']) {
        $this->spiedFindFilter = $filter;
        $this->spiedFindCols = $cols;
      }

      public function delete($id) {}
    };
    return $p;
  }
}