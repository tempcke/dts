<?php

namespace HomeCEU\DTS\Entity;

use HomeCEU\DTS\Entity;
use HomeCEU\DTS\EntityHelperTrait;

class Template implements Entity {
  public $templateId;
  public $templateKey;
  public $docType;
  public $name;
  public $author;
  public $createdAt;
  public $body;

  use EntityHelperTrait;

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

  public static function fromState(array $state): Template {
    $entity = new Template;
    self::buildFromState($entity, $state);
    return $entity;
  }
}