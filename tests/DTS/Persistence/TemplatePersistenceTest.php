<?php


namespace HomeCEU\Tests\DTS\Persistence;


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

  protected function newTemplate(array $overwrite): Template {
    $base = [
        'templateId' => self::faker()->uuid,
        'docType' => $this->docType,
        'templateKey' => uniqid(__FUNCTION__),
        'name' => self::faker()->monthName,
        'author' => self::faker()->name,
        'createdAt' => new \DateTime('yesterday'),
        'body' => 'hi {{name}}'
    ];
    $templateData = array_merge($base, $overwrite);

    $t = Template::fromState($templateData);
    $this->p->persist($t->toArray());
    if (!in_array($templateData['docType'], $this->cleanupDocTypes)) {
      $this->addCleanupDoctype($templateData['docType']);
    }
    return $t;
  }

  public function testSearchForTemplateLikeDoctype() {
    $this->searchTemplateTest('docType', $this->docType);
  }
  public function testSearchForTemplateLikeKey() {
    $this->searchTemplateTest('templateKey', __FUNCTION__);
  }
  public function testSearchForTemplateLikeName() {
    $this->searchTemplateTest('name', __FUNCTION__);
  }
  public function testSearchForTemplateLikeAuthor() {
    $this->searchTemplateTest('author', __FUNCTION__);
  }

  protected function searchTemplateTest(string $field, string $prefix) {
    $subStr = self::faker()->firstName;
    $this->newTemplate([
        $field => implode('-',[$prefix, 'foo', uniqid()])
    ]);
    $t = $this->newTemplate([
        $field => implode('-',[$prefix, $subStr, uniqid()])
    ]);
    $results = $this->p->search([$field], $subStr);
    $this->assertSearchMatches($results, $t);
  }

  protected function uniqueName($prefix, $substring) {
    return implode('-',[$prefix, $substring, uniqid()]);
  }

  public function testSearchForTemplateByStringSpanningTypeKeyNameAuthor() {
    $this->newTemplate([
        'docType' => $this->uniqueName($this->docType, 'nil'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'nil'),
        'name' => $this->uniqueName(self::faker()->name, 'nil'),
        'author' => $this->uniqueName(self::faker()->name, 'nil')
    ]);
    $t = $this->newTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);
    $results = $this->p->search(
        ['docType','templateKey','name','author'],
        'foo bar baz fin'
    );
    $this->assertSearchMatches($results, $t);
  }

  public function testSearchWithMultipleResults() {
    $this->newTemplate([
        'docType' => $this->uniqueName($this->docType, 'nil'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'nil'),
        'name' => $this->uniqueName(self::faker()->name, 'nil'),
        'author' => $this->uniqueName(self::faker()->name, 'nil')
    ]);
    $this->newTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);$this->newTemplate([
        'docType' => $this->uniqueName($this->docType, 'foo'),
        'templateKey' => $this->uniqueName(__FUNCTION__, 'bar'),
        'name' => $this->uniqueName(self::faker()->name, 'baz'),
        'author' => $this->uniqueName(self::faker()->name, 'fin')
    ]);
    $results = $this->p->search(
        ['docType','templateKey','name','author'],
        'foo bar baz fin'
    );
    Assert::assertCount(2, $results);
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
    $this->expectException(\Exception::class);
    $this->p->delete($record['templateId']);
  }
}
