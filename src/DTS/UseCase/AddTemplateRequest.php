<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\AbstractEntity;

class AddTemplateRequest extends AbstractEntity {
  public $type;
  public $key;
  public $author;
  public $body;

  protected static function keys(): array {
    return [
        'type',
        'key',
        'author',
        'body',
    ];
  }

  public function isValid(): bool {
    return !empty($this->type)
        && !empty($this->key)
        && !empty($this->author)
        && !empty($this->body);
  }
}
