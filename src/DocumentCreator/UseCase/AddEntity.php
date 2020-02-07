<?php

namespace HomeCEU\DocumentCreator\UseCase;

use HomeCEU\DocumentCreator\Repository\EntityRepository;

class AddEntity {
  /** @var EntityRepository */
  private $repo;

  public function __construct(EntityRepository $repo) {
    $this->repo = $repo;
  }

  public function add($type, $key, $data): array {
    $entity = $this->repo->newEntity($type, $key, $data);
    $this->repo->save($entity);
    return $entity->toArray();
  }
}