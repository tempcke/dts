<?php


namespace HomeCEU\DTS;


trait EntityHelperTrait {

  protected static function buildFromState(Entity $entity, array $state) {
    foreach ($entity->keys() as $k) {
      if (array_key_exists($k, $state)) {
        $entity->{$k} = static::valueFromState($state, $k);
      }
    }
  }

  protected static function valueFromState(array $state, string $key) {
    if ($key == 'createdAt' && is_string($state[$key]))
      return new \DateTime($state[$key]);
    return $state[$key];
  }

  public function toArray(): array {
    $result = array();

    foreach ($this->keys() as $k) {
      $result[$k] = $this->{$k};
    }

    return $result;
  }
}