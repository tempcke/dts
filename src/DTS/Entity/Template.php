<?php

namespace HomeCEU\DTS\Entity;


use DateTime;
use HomeCEU\DTS\AbstractEntity;

class Template extends AbstractEntity {
  public $templateId;
  public $templateKey;
  public $docType;
  public $name;
  public $author;
  /** @var DateTime */
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
