<?php

namespace HomeCEU\Tests\DTS\Repository;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence\TemplatePersistence;
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

  private function buildTemplate($key, $name, $createdAt) {
    $t = $this->fakeTemplateArray($this->docType, $key);
    $t['createdAt']= new \DateTime($createdAt);
    $t['name']=$name;
    return $t;
  }
}