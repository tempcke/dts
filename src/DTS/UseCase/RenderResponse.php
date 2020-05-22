<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class RenderResponse extends AbstractEntity {
  public $path;
  public $contentType;

  protected static function keys(): array {
    return [
        'path',
        'contentType'
    ];
  }
}
