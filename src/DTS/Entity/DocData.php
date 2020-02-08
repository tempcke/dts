<?php


namespace HomeCEU\DTS\Entity;


class DocData {
  public $dataId;
  public $docType;
  public $dataKey;
  public $createdAt;
  public $data;

  private $keys = [
      'dataId',
      'docType',
      'dataKey',
      'createdAt',
      'data'
  ];

  public static function fromState(array $state): DocData {
    $entity = new DocData;
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