<?php

namespace HomeCEU\DocumentCreator\UseCase;

use HomeCEU\DocumentCreator\Repository\DocDataRepository;

class AddDocData {
  /** @var DocDataRepository */
  private $repo;

  public function __construct(DocDataRepository $repo) {
    $this->repo = $repo;
  }

  public function add($type, $key, $data): array {
    $entity = $this->repo->newEntity($type, $key, $data);
    $this->repo->save($entity);
    return $entity->toArray();
  }
}