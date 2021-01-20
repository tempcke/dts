<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase\Render;


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

  public function isValid(): bool {
    return !empty($this->template) && !is_null($this->data);
  }
}
