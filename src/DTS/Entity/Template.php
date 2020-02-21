<?php

namespace HomeCEU\DTS\Entity;

use HomeCEU\DTS\Entity;

class Template extends Entity {
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

  public static function fromState(array $state): Template {
    $entity = new Template;
    self::buildFromState($entity, $state);
    return $entity;
  }
}