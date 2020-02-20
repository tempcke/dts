<?php


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Persistence;
use Ramsey\Uuid\Uuid;

class TemplatePersistence implements Persistence {

  public function generateId() {
    return Uuid::uuid1();
  }

  public function persist($data) {
    // TODO: Implement persist() method.
  }

  public function retrieve($id, array $cols = ['*']) {
    // TODO: Implement retrieve() method.
  }

  public function find(array $filter, array $cols = ['*']) {
    // TODO: Implement find() method.
  }

  public function delete($id) {
    // TODO: Implement delete() method.
  }
}