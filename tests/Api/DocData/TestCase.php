<?php


namespace HomeCEU\Tests\Api\DocData;


use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence\DocDataPersistence;

class TestCase extends \HomeCEU\Tests\Api\TestCase {

  /** @var string */
  protected $docType;

  /** @var DocDataPersistence */
  protected $persistence;

  /** @var DiContainer */
  protected $di;

  public function setUp(): void {
    parent::setUp();
    $this->docType = (new \ReflectionClass($this))->getShortName().'-'.time();
    $this->di = new DiContainer();
    $this->persistence = new DocDataPersistence($this->di->dbConnection);
  }

  public function tearDown(): void {
    $db = $this->di->dbConnection;
    $db->deleteWhere(
        DocDataPersistence::TABLE_DOCDATA,
        ['doc_type'=>$this->docType]
    );
    parent::tearDown();
  }

  protected function addDocDataFixture($dataKey) {
    $this->persistence->persist([
        'docType' => $this->docType,
        'dataKey' => $dataKey,
        'createdAt' => new \DateTime(),
        'dataId' => uniqid(),
        'data' => ['foo']
    ]);
  }
}