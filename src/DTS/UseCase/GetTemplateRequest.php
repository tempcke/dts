<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class GetTemplateRequest extends AbstractEntity {
  public $templateId;
  public $docType;
  public $templateKey;

  protected static function keys(): array {
    return [
        'templateId',
        'docType',
        'templateKey'
    ];
  }

  public function isValid(): bool {
    return !empty($this->templateId) || (!empty($this->docType) && !empty($this->templateKey));
  }
}
