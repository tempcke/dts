<?php


namespace HomeCEU\DTS\Persistence\InMemory;


use HomeCEU\DTS\Persistence\InMemory;

class DocDataPersistence extends InMemory {

  public function getTable() {
    return 'entity';
  }

  public function idColumns(): array {
    return ['entityId'];
  }
}