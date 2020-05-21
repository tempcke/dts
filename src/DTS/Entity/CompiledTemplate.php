<?php declare(strict_types=1);


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;

class CompiledTemplate extends AbstractEntity {
  public $templateId;
  public $body;
  public $createdAt;

  protected static function keys(): array {
    return [
        'templateId',
        'body',
    ];
  }
}
