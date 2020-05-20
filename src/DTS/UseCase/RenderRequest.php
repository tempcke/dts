<?php


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;
use HomeCEU\DTS\EntityHelperTrait;

class RenderRequest extends AbstractEntity {
  public $docType;
  public $templateId;
  public $templateKey;
  public $dataId;
  public $dataKey;
  public $format;

  use EntityHelperTrait;

  protected static function keys(): array {
    return [
        'docType',
        'templateId',
        'templateKey',
        'dataId',
        'dataKey',
        'format'
    ];
  }

  public static function fromState(array $state): self {
    $entity = new RenderRequest();
    self::buildFromState($entity, $state);
    return $entity;
  }

  public function isValid() {
    return $this->isValidTemplate() && $this->isValidDocData();
  }

  private function isValidTemplate() {
    if (!empty($this->templateId)) return true;
    if ((!empty($this->docType) && !empty($this->templateKey))) return true;
    return false;
  }

  private function isValidDocData() {
    if (!empty($this->dataId)) return true;
    if ((!empty($this->docType) && !empty($this->dataKey))) return true;
    return false;
  }
}
