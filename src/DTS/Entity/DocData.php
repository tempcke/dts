<?php


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;
use HomeCEU\DTS\EntityHelperTrait;

class DocData extends AbstractEntity {
  public $dataId;
  public $docType;
  public $dataKey;
  public $createdAt;
  public $data;

  use EntityHelperTrait;

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
