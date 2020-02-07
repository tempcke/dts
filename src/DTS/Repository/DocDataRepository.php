<?php

namespace HomeCEU\DTS\Repository;

use DateTime;
use HomeCEU\DTS\Entity\DocData as Entity;
use HomeCEU\DTS\Persistence;

class DocDataRepository {
  /** @var Persistence */
  private $persistence;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
  }

  public function save(Entity $entity) {
    $this->persistence->persist($entity->toArray());
  }

  public function newEntity($type, $key, $data) {
    return Entity::fromState(
        [
            'entityId'   => $this->persistence->generateId(),
            'entityType' => $type,
            'entityKey'  => $key,
            'data'       => $data,
            'createdAt'  => (new DateTime())->format(DateTime::ISO8601)
        ]
    );
  }
}