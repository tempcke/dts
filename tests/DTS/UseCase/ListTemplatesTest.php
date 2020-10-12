<?php


namespace HomeCEU\Tests\DTS\UseCase;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\ListTemplates;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class ListTemplatesTest extends TestCase {
  /** @var Db\Connection */
  private $db;

  /** @var TemplatePersistence */
  private $p;

  /** @var TemplateRepository */
  private $repo;

  /** @var ListTemplates */
  private $usecase;

  protected function setUp(): void {
    parent::setUp();
    $this->db = Db::connection();
    $this->db->beginTransaction();
    $this->p = new TemplatePersistence($this->db);
    $this->repo = new TemplateRepository($this->p, $this->compiledTemplatePersistence());
    $this->usecase = new ListTemplates($this->repo);
  }

  protected function tearDown(): void {
    parent::tearDown();
    $this->db->rollBack();
  }

  public function testGetTemplateListBySearchString() {
    $count = 3;
    $searchString = "cert not cool bob";

    // matching templates
    for ($i=1; $i<=$count; $i++) {
      $this->p->persist(array_merge(
          $this->fakeTemplateArray(),
          [
              'docType' => 'cert',
              'templateKey' => 'to key or not to key '.$i,
              'name' => 'some cool name',
              'author' => 'uncle bob'
          ]
      ));
    }
    // non matching template
    $this->p->persist($this->fakeTemplateArray());
    $templates = $this->usecase->search($searchString);
    Assert::assertCount($count, $templates);
  }

  protected function compiledTemplatePersistence(): Persistence {
    return new class extends Persistence\InMemory {

      public function getTable() {
        return Persistence\CompiledTemplatePersistence::TABLE;
      }

      public function idColumns(): array {
        return [ Persistence\CompiledTemplatePersistence::ID_COL ];
      }
    };
  }
}