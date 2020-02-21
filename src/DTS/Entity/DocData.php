<?php


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\Entity;

class DocData extends Entity {
  public $dataId;
  public $docType;
  public $dataKey;
  public $createdAt;
  public $data;

  protected static function keys(): array {
    return [
        'dataId',
        'docType',
        'dataKey',
        'createdAt',
        'data'
    ];
  }

  public static function fromState(array $state): DocData {
    $entity = new DocData;
    self::buildFromState($entity, $state);
    return $entity;
  }
}