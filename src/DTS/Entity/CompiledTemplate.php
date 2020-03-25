<?php declare(strict_types=1);


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;
use HomeCEU\DTS\EntityHelperTrait;

class CompiledTemplate extends AbstractEntity {
  public $templateId;
  public $body;
  public $createdAt;

  use EntityHelperTrait;

  protected static function keys(): array {
    return [
        'templateId',
        'body',
    ];
  }

  public static function fromState(array $state): self {
    $entity = new self();
    self::buildFromState($entity, $state);
    return $entity;
  }
}
