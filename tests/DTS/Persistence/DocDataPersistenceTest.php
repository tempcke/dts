<?php


namespace HomeCEU\Tests\DTS\Persistence;


use HomeCEU\DTS\Db;
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
    $this->db = Db::connection();
    $this->p = new DocDataPersistence($this->db);
  }

  public function tearDown(): void {
    parent::tearDown();
    foreach ($this->cleanupCalls as $func) {
      call_user_func($func);
    }
  }

  public function testPersist() {
    $data = $this->docData();
    $this->p->persist($data->toArray());
    $table = DocDataPersistence::TABLE_DOCDATA;
    $matchingRecordCount = $this->db->count(
        $table,
        'data_id=:data_id',
        [':data_id'=>$data->dataId]
    );
    Assert::assertEquals(1, $matchingRecordCount);
    $this->addCleanup(function() use($table, $data){
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