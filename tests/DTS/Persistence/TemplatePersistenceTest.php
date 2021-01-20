<?php


namespace HomeCEU\Tests\DTS\Persistence;


use Exception;
use HomeCEU\DTS\Db;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class TemplatePersistenceTest extends TestCase {

  /** @var TemplatePersistence */
  protected $p;

  protected $docType;

  protected $cleanupDocTypes = [

  ];

  /** @var Db\Connection */
  protected $db;

  protected function setUp(): void {
    parent::setUp();
    $this->db = Db::newConnection();
    $this->p = new TemplatePersistence($this->db);
    $this->docType = 'TemplatePersistenceTest-'.time();
    $this->addCleanupDoctype($this->docType);
  }

  protected function tearDown(): void {
    foreach ($this->cleanupDocTypes as $docType) {
      $this->db->deleteWhere('template', ['doc_type'=>$docType]);
    }
    parent::tearDown();
  }

  protected function addCleanupDoctype($doctype) {
    $this->cleanupDocTypes[] = $doctype;
  }

  protected function newPersistedTemplate(array $overwrite): Template {
    if (empty($overwrite['docType'])) {
      $overwrite['docType'] = $this->docType;
    }

    $t = $this->newTemplate($overwrite);
    $this->p->persist($t->toArray());
    if (!in_array($t->docType, $this->cleanupDocTypes)) {
      $this->addCleanupDoctype($t->docType);
    }
    return $t;
  }

  public function testFilterBySearchString() {
    $this->newPersistedTemplate([
        'docType' => $this->uniqueName($this->docType, 'nil'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'nil'),
        'name' => $this->uniqueName(self::faker()->name, 'nil'),
        'author' => $this->uniqueName(self::faker()->name, 'nil')
    ]);
    $t = $this->newPersistedTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);

    $results = $this->p->filterBySearchString('foo bar baz fin');

    $this->assertSearchMatches($results, $t);
  }

  public function testFilterBySearchStringWithMultipleResults() {
    $this->newPersistedTemplate([
        'docType' => $this->uniqueName($this->docType, 'nil'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'nil'),
        'name' => $this->uniqueName(self::faker()->name, 'nil'),
        'author' => $this->uniqueName(self::faker()->name, 'nil')
    ]);
    $this->newPersistedTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);
    $this->newPersistedTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);
    $results = $this->p->filterBySearchString('foo bar baz fin');
    Assert::assertCount(2, $results);
  }

  public function testListDocTypes() {
    // Load Fixture Data
    $fixtureData = [
        [ 'docType' => 'A', 'templateKey' => 'k1' ], // 3 versions of k1
        [ 'docType' => 'A', 'templateKey' => 'k1' ],
        [ 'docType' => 'A', 'templateKey' => 'k1' ],
        [ 'docType' => 'A' ], // will be unique key
        [ 'docType' => 'A' ], // so should be 3 A's
        [ 'docType' => 'B' ],
        [ 'docType' => 'B' ], // 2 B's
        [ 'docType' => 'C' ], // 1 C
    ];
    $expectedDoctypeCounts = [
        'A' => 3,
        'B' => 2,
        'C' => 1
    ];
    foreach ($fixtureData as $data) {
      $this->newPersistedTemplate($data);
    }

    // Thing we are actually testing...
    $docTypes = $this->p->listDocTypes();

    // Assertions
    Assert::assertSameSize($expectedDoctypeCounts, $docTypes);
    foreach ($docTypes as $row) {
      Assert::assertArrayHasKey($row['docType'], $expectedDoctypeCounts);
      Assert::assertEquals(
          $expectedDoctypeCounts[$row['docType']],
          $row['templateCount']
      );
    }
  }


  protected function assertSearchMatches($results, Template $t) {
    Assert::assertCount(1, $results);
    Assert::assertArrayHasKey('templateId', $results[0]);
    Assert::assertEquals($t->templateId, $results[0]['templateId']);
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
    $this->expectException(Exception::class);
    $this->p->delete($record['templateId']);
  }
}
