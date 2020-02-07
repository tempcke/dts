<?php


namespace HomeCEU\DocumentCreator\Entity;


class TemplateData {
  public $entityId;
  public $entityType;
  public $entityKey;
  public $createdAt;
  public $data;

  private $keys = [
      'entityId',
      'entityType',
      'entityKey',
      'createdAt',
      'data'
  ];

  public static function fromState(array $state): TemplateData {
    $entity = new TemplateData;
    foreach ($entity->keys as $k) {
      if (array_key_exists($k, $state)) {
        $entity->{$k} = $state[$k];
      }
    }
    return $entity;
  }

  public function toArray() {
    $result = array();

    foreach ($this->keys as $k) {
      $result[$k] = $this->{$k};
    }

    return $result;
  }
}