<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class HotRenderRequest extends AbstractEntity {
  public $format;
  public $template;
  public $docType;
  public $data;

  protected static function keys(): array {
    return [
        'format',
        'template',
        'docType',
        'data',
    ];
  }

  public function isValid(): bool {
    return (!empty($this->template) && !is_null($this->data));
  }
}
