<?php


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;

class DocData extends AbstractEntity {
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
}
