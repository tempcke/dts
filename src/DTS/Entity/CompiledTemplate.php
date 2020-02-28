<?php declare(strict_types=1);


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\Entity;
use HomeCEU\DTS\EntityHelperTrait;

class CompiledTemplate implements Entity {
  public $templateId;
  public $body;
  public $createdAt;

  use EntityHelperTrait;

  protected static function key(): array {
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
