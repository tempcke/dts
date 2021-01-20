<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase\Render;


use HomeCEU\DTS\AbstractEntity;

class HotRenderRequest extends AbstractEntity {
  public $requestId;
  public $format;

  protected static function keys(): array {
    return [
        'requestId',
        'format'
    ];
  }
}
