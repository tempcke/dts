<?php


namespace HomeCEU\Tests\DTS\Persistence;


use HomeCEU\DTS\Db;
use HomeCEU\DTS\Db\Config as DbConfig;
use HomeCEU\DTS\Entity\DocData;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\Tests\DTS\TestCase;
use HomeCEU\Tests\Faker;
use PHPUnit\Framework\Assert;

class DocDataPersistenceTest extends TestCase {

  /** @var  DocDataPersistence */
  private $p;

  /** @var Db\Connection */
  private $db;

  private $cleanupCalls = [];

  public function setUp(): void {
    parent::setUp();
    $this->db = Db::newConnection();
    $this->p = new DocDataPersistence($this->db);
  }

  public function tearDown(): void {
    parent::tearDown();
    foreach ($this->cleanupCalls as $func) {
      call_user_func($func);
    }
  }

  public function testGenerateId() {
    $id1 = $this->p->generateId();
    $id2 = $this->p->generateId();
    Assert::assertNotEmpty($id1);
    Assert::assertNotEquals($id1, $id2);
  }

  public function testPersist() {
    $data = $this->docData();
    $this->persist($data);
    Assert::assertEquals(
        1,
        $this->db->count(
            DocDataPersistence::TABLE_DOCDATA,
            'data_id=:data_id',
            [':data_id'=>$data->dataId]
        )
    );
  }

  public function testCanRetrieveSavedRecord() {
    $data = $this->docData();
    $this->persist($data);
    $retrieved = $this->p->retrieve($data->dataId);
    var_dump($retrieved);
  }

  protected function persist(DocData $data) {
    $this->p->persist($data->toArray());
    $this->addCleanup(function() use($data){
      $table = DocDataPersistence::TABLE_DOCDATA;
      $this->db->query("DELETE FROM {$table} WHERE data_id=?", $data->dataId);
    });
  }

  protected function addCleanup(callable $func) {
    array_push($this->cleanupCalls, $func);
  }

  protected function docData() {
    $entityState = $this->fakeEntity();
    return DocData::fromState($entityState);
  }

  protected function fakeEntity() {
    $fake = Faker::generator();
    return [
        'dataId'   => $fake->uuid,
        'docType' => 'courseCompletionCertificate',
        'dataKey'  => $fake->md5,
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