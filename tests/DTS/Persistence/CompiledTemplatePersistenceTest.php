<?php declare(strict_types=1);


namespace DTS\Persistence;


use HomeCEU\DTS\Db;
use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class CompiledTemplatePersistenceTest extends TestCase {
  protected $persistence;
  protected $db;
  protected $template;

  protected function setUp(): void {
    parent::setUp();
    $this->db = Db::newConnection();
    $this->db->beginTransaction();
    $this->persistence = new CompiledTemplatePersistence($this->db);
    $this->template = $this->fakeTemplateArray();
    (new TemplatePersistence($this->db))->persist($this->template);
  }

  protected function tearDown(): void {
    parent::tearDown();
    $this->db->rollBack();
  }

  public function testCanRetrievePersistedRecord(): void {
    $cTemplate = $this->fakeCompiledTemplate();
    $this->persistence->persist($cTemplate);

    $retrieved = $this->persistence->retrieve($cTemplate['templateId']);
    Assert::assertEquals($cTemplate, $retrieved);
  }

  private function fakeCompiledTemplate(): array {
    return [
        'templateId' => $this->template['templateId'],
        'body' => 'a template body',
        'createdAt' => new \DateTime('yesterday'),
    ];
  }
}
