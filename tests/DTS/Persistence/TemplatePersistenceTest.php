<?php


namespace HomeCEU\Tests\DTS\Persistence;


use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class TemplatePersistenceTest extends TestCase {

  /** @var TemplatePersistence */
  protected $persistence;

  public function setUp(): void {
    parent::setUp();
    $this->persistence = new TemplatePersistence();
  }

  public function testGenerateId() {
    $id1 = $this->persistence->generateId();
    $id2 = $this->persistence->generateId();
    Assert::assertNotEmpty($id1);
    Assert::assertNotEmpty($id2);
    Assert::assertNotEquals($id1, $id2);
  }
}