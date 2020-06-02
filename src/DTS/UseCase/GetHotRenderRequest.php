<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class GetHotRenderRequest extends AbstractEntity {
  public $requestId;
  public $format;

  protected static function keys(): array {
    return [
        'requestId',
        'format'
    ];
  }
}
