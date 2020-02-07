<?php


namespace HomeCEU\DocumentCreator\Persistence\InMemory;


use HomeCEU\DocumentCreator\Persistence\InMemory;

class DocDataPersistence extends InMemory {

  public function getTable() {
    return 'entity';
  }

  public function idColumns(): array {
    return ['entityId'];
  }
}