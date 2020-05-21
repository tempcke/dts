<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS;


use HomeCEU\DTS\AbstractEntity;
use PHPUnit\Framework\Assert;

class AbstractEntityTest extends TestCase {
  const STATE = ['key' => 123, 'body' => 'abc', 'createdAt' => null];

  private $entity;

  protected function setUp(): void {
    parent::setUp();
    $this->entity = $this->getTestEntity();
  }

  public function testBuildFromState(): void {
    $entity = $this->entity::fromState(self::STATE);

    Assert::assertEquals(self::STATE['key'], $entity->key);
    Assert::assertEquals(self::STATE['body'], $entity->body);
    Assert::assertEquals(self::STATE['createdAt'], $entity->createdAt);
  }

  public function testToArray(): void {
    $entity = $this->entity::fromState(self::STATE);
    Assert::assertEquals(self::STATE, $entity->toArray());
  }

  public function testCreatedAtConvertsStringDateTime() {
    $iso8601 = self::faker()->iso8601;
    $state = array_merge(self::STATE, ['createdAt' => $iso8601]);
    $expectedState = array_merge(self::STATE, ['createdAt' => new \DateTime($iso8601)]);
    $entity = $this->entity::fromState($state);
    Assert::assertEquals($expectedState, $entity->toArray());
  }

  private function getTestEntity() {
    return new class extends AbstractEntity {
      public $key;
      public $body;
      public $createdAt;

      protected static function keys(): array {
        return ['key', 'body', 'createdAt'];
      }
    };
  }
}
