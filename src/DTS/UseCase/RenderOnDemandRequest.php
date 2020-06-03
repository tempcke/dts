<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class RenderOnDemandRequest extends AbstractEntity {
  public $docType;
  public $templateKey;
  public $docData;

  protected static function keys(): array {
    return ['docType', 'templateKey', 'docData'];
  }

  public function isValid(): bool {
    return !(empty($this->docType)
        || empty($this->templateKey)
        || is_null($this->docData));
  }
}
