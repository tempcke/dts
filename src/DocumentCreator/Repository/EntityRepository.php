<?php

namespace HomeCEU\DocumentCreator\Repository;

use DateTime;
use HomeCEU\DocumentCreator\Entity\TemplateData as Entity;
use HomeCEU\DocumentCreator\Persistence;

class EntityRepository {
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