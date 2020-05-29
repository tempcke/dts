<?php declare(strict_types=1);


namespace HomeCEU\DTS\Entity;


use HomeCEU\DTS\AbstractEntity;

class HotRender extends AbstractEntity {
  public $requestId;
  public $template;
  public $data;
  public $createdAt;

  protected static function keys(): array {
    return [
        'requestId',
        'template',
        'data',
        'createdAt'
    ];
  }
}
