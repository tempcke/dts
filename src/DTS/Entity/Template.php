<?php

namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;

class Template extends AbstractEntity {
  public $templateId;
  public $templateKey;
  public $docType;
  public $name;
  public $author;
  public $createdAt;
  public $body;

  protected static function keys(): array {
    return [
        'templateId',
        'docType',
        'templateKey',
        'createdAt',
        'name',
        'author',
        'body'
    ];
  }
}
