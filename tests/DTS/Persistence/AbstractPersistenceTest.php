<?php


namespace HomeCEU\Tests\DTS\Persistence;


use HomeCEU\DTS\Persistence\AbstractPersistence;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class AbstractPersistenceTest extends TestCase {
  public function hydrated() {
    return [
        "firstName" => "Fred",
        "birthDay" => new \DateTime("2000-01-01"),
        "location" => ["state" => "TX", "city" => "Dallas"]
    ];
  }

  public function mySqlForm() {
    return [
        "first_name" => "Fred",
        "birth_day" => "2000-01-01 00:00:00",
        "location" => '{"state":"TX","city":"Dallas"}'
    ];
  }

  public function keymap() {
    return [
        'firstName' => 'first_name',
        'lastName' => 'last_name',
        'birthDay'  => 'birth_day',
    ];
  }

  public function testFlatten() {
    $p = $this->persistence();
    $p->useKeyMap($this->keymap());
    $flattened = $p->flatten($this->hydrated());
    Assert::assertEquals($this->mySqlForm(), $flattened);
  }

  public function testHydrateFromMySQL() {
    $p = $this->persistence();
    $p->useKeyMap($this->keymap());
    $hydrated = $p->hydrate($this->mySqlForm());
    Assert::assertEquals($this->hydrated(), $hydrated);
  }

  protected function persistence() {
    return new class extends AbstractPersistence {
      public function generateId() {}
      public function persist($data) {}
      public function retrieve($id, array $cols=[]) {}
      public function delete($id) {}
      public function find(array $filter, array $cols=[]) {}
    };
  }
}