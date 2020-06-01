<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class AddHotRenderRequest extends AbstractEntity {
  public $template;
  public $data;
  public $docType;

  protected static function keys(): array {
    return [
        'template',
        'data',
        'docType'
    ];
  }
}
