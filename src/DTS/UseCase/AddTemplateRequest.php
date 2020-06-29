<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class AddTemplateRequest extends AbstractEntity {
  public $docType;
  public $templateKey;
  public $author;
  public $body;

  protected static function keys(): array {
    return [
        'docType',
        'templateKey',
        'author',
        'body',
    ];
  }

  public static function fromState(array $state): AbstractEntity {
    return parent::fromState($state)->validate();
  }

  protected function validate(): self {
    if (empty($this->docType)
        || empty($this->templateKey)
        || empty($this->author)
        || empty($this->body)) {
      throw new InvalidAddTemplateRequestException("Required values: " . implode(', ', self::keys()));
    }
    return $this;
  }
}
