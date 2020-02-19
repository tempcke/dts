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
        $entity->{$k} = static::valueFromState($state, $k);
      }
    }
    return $entity;
  }

  protected static function valueFromState(array $state, string $key) {
    if ($key == 'createdAt')
      return new \DateTime($state[$key]);
    return $state[$key];
  }

  public function toArray() {
    $result = array();

    foreach ($this->keys as $k) {
      $result[$k] = $this->{$k};
    }

    return $result;
  }
}