<?php


namespace HomeCEU\Tests\DTS\Persistence;


use HomeCEU\DTS\Db;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class TemplatePersistenceTest extends TestCase {

  /** @var TemplatePersistence */
  protected $p;

  protected $docType;

  /** @var Db\Connection */
  protected $db;

  public function setUp(): void {
    parent::setUp();
    $this->db = Db::newConnection();
    $this->p = new TemplatePersistence($this->db);
    $this->docType = 'TemplatePersistenceTest-'.time();
  }

  public function tearDown(): void {
    $this->db->deleteWhere('template', ['doc_type'=>$this->docType]);
    parent::tearDown();
  }

  public function testGenerateId() {
    $id1 = $this->p->generateId();
    $id2 = $this->p->generateId();
    Assert::assertNotEmpty($id1);
    Assert::assertNotEmpty($id2);
    Assert::assertNotEquals($id1, $id2);
  }

  public function testCanRetrievePersistedRecord() {
    $record = $this->fakeTemplateArray($this->docType);
    $this->p->persist($record);
    $retrieved = $this->p->retrieve($record['templateId']);
    Assert::assertEquals($record, $retrieved);
  }

  public function testNoDelete() {
    $record = $this->fakeTemplateArray($this->docType);
    $this->p->persist($record);
    $this->expectException(\Exception::class);
    $this->p->delete($record['templateId']);
  }
}