<?php

namespace HomeCEU\Tests\DTS\Repository;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class TemplateRepositoryTest extends TestCase {
  /** @var Db\Connection */
  private $db;

  /** @var TemplatePersistence */
  private $p;

  /** @var TemplateRepository */
  private $repo;

  private $docType;

  public function setUp(): void {
    parent::setUp();
    $this->db = Db::connection();
    $this->p = new TemplatePersistence($this->db);
    $this->repo = new TemplateRepository($this->p);
    $this->docType = 'TemplateRepositoryTest-' . time();
  }

  public function tearDown(): void {
    $this->db->deleteWhere('template', ['doc_type' => $this->docType]);
    parent::tearDown();
  }

  public function testGetNewestTemplateByKey() {
    $key = __FUNCTION__;
    $this->p->persist($this->buildTemplate($key,'B','2000-01-02'));
    $this->p->persist($this->buildTemplate($key,'A','2000-01-01'));
    $this->p->persist($this->buildTemplate($key,'C','2000-01-03'));
    $template = $this->repo->getTemplateByKey($this->docType, $key);
    Assert::assertInstanceOf(Template::class, $template);
    Assert::assertEquals('C', $template->name);
  }

  public function testGetTemplateById() {
    $key = __FUNCTION__;
    $t = $this->fakeTemplateArray($this->docType, $key);
    $this->p->persist($this->fakeTemplateArray($this->docType));
    $this->p->persist($t);
    $this->p->persist($this->fakeTemplateArray($this->docType));
    $template = $this->repo->getTemplateById($t['templateId']);
    Assert::assertInstanceOf(Template::class, $template);
    Assert::assertEquals($t['templateId'], $template->templateId);
  }
  
  public function testSave() {
    $templateArray = $this->fakeTemplateArray($this->docType, __FUNCTION__);
    $template = Template::fromState($templateArray);
    $this->repo->save($template);
    $fromDb = $this->repo->getTemplateById($template->templateId);
    Assert::assertEquals($templateArray, $fromDb->toArray());
  }

  public function testFindByDocType() {
    $t = $this->buildTemplate(__FUNCTION__, 'A', '2000-01-01');
    $this->p->persist($t);
    $fromDb = $this->repo->findByDocType($this->docType);
    Assert::assertCount(1, $fromDb);
    Assert::assertEquals($t, $fromDb[0]->toArray());
  }

  public function test_LookupId_shouldThrowExceptionIfNoneFound() {
    $this->expectException(RecordNotFoundException::class);
    $this->repo->lookupId($this->docType, __FUNCTION__);
  }
  public function testLookupIdFromKey() {
    $p = $this->fakePersistence('template', 'templateId');
    $p->persist([
        'docType' => 'dt',
        'templateId' => 'tid',
        'templateKey' => 'tk',
        'body'=>'Hi {{name}}'
    ]);
    $repo = new TemplateRepository($p);
    Assert::assertEquals('tid', $repo->lookupId('dt','tk'));
  }
  public function testGetNewestTemplateIdWhenLookupByKey() {
    $key = __FUNCTION__;
    $a = $this->buildTemplate($key, 'A', '2000-01-01');
    $b = $this->buildTemplate($key, 'B', '2000-01-02');
    $c = $this->buildTemplate($key, 'C', '2000-01-03');
    $this->p->persist($b);
    $this->p->persist($a);
    $this->p->persist($c);
    $id = $this->repo->lookupId($this->docType, $key);
    Assert::assertEquals($c['templateId'], $id);
  }

  private function buildTemplate($key, $name, $createdAt) {
    $t = $this->fakeTemplateArray($this->docType, $key);
    $t['createdAt']= new \DateTime($createdAt);
    $t['name']=$name;
    return $t;
  }
}