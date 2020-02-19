<?php


namespace HomeCEU\DTS\Persistence;

use HomeCEU\DTS\Persistence;
use Ramsey\Uuid\Uuid;

abstract class InMemory implements Persistence {
  private $data = [];

  abstract public function getTable();
  abstract public function idColumns(): array;

  public function generateId() {
    return $this->uuid1()->toString();

  }

  public function persist($data) {
    $this->data[$this->getIdFromData($data)] = $data;
  }

  public function retrieve($id, array $cols=['*']) {
    if (!$this->has($id))
      throw new \OutOfBoundsException("No such user {$id}");

    return $this->data[$id];
  }

  public function delete($id) {
    if (!$this->has($id))
      throw new \OutOfBoundsException("No such user {$id}");

    unset($this->data[$id]);
  }

  protected function uuid1() {
    return Uuid::uuid1();
  }

  private function getIdFromData($data) {
    $id = [];
    foreach($this->idColumns() as $key) {
      array_push($id, $data[$key]);
    }
    return implode('-',$id);
  }

  /**
   * @param $id
   * @return bool
   */
  protected function has($id): bool {
    return array_key_exists($id, $this->data);
  }

  public function find(array $filter, array $cols=['*']) {
    $matching = [];
    foreach ($this->data as $id=>$entity) {
      foreach ($filter as $k=>$v) {
        if (!empty($entity[$k]) && $entity[$k]==$v) {
          array_push($matching, $v);
        }
      }
    }
    return $matching;
  }
}