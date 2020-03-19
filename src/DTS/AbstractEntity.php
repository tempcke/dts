<?php declare(strict_types=1);


namespace HomeCEU\DTS;


abstract class AbstractEntity implements Entity {

  abstract protected static function keys();

  public function toArray(): array {
    $result = [];
    foreach ($this->keys() as $k) {
      $result[$k] = $this->{$k};
    }
    return $result;
  }
}
