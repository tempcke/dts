<?php


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Persistence;

abstract class AbstractPersistence implements Persistence {
  /** @var array */
  private $hydratedToDbMap;
  /** @var array */
  private $dbToHydratedMap;

  /**
   * @param array $map map of hydratedKey => db_key
   */
  public function useKeyMap(array $map) {
    $this->hydratedToDbMap = $map;
    $this->dbToHydratedMap = array_flip($map);
  }

  protected function hydratedKey($db_key) {
    if (array_key_exists($db_key, $this->dbToHydratedMap)) {
      return $this->dbToHydratedMap[$db_key];
    }
    return $db_key;
  }
  protected function dbKey($hydratedKey) {
    if (array_key_exists($hydratedKey, $this->hydratedToDbMap)) {
      return $this->hydratedToDbMap[$hydratedKey];
    }
    return $hydratedKey;
  }

  public function flatten(array $entity) {
    $result = array();

    foreach ($entity as $k => $v) {
      if ($v instanceof \DateTime) {
        $value = $v->format('Y-m-d H:i:s');
      } elseif (is_array($v)) {
        $value = json_encode($v);
      } else {
        $value = $v;
      }
      $key = $this->dbKey($k);
      $result[$key] = $value;
    }

    return $result;
  }

  public function hydrate($entity) {
    $result = array();

    foreach ($entity as $k => $v) {
      $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $v);
      if ($dateTime !== FALSE) {
        $value = $dateTime;
      } elseif ($this->isJson($v)) {
        $value = $this->jsonDecodeAsArray($v);
      } else {
        $value = $v;
      }
      $key = $this->hydratedKey($k);
      $result[$key] = $value;
    }

    return $result;
  }

  private function isJson($v) {
    return is_array(json_decode($v, true));
  }

  private function jsonDecodeAsArray($json): array {
    return json_decode($json, true);
  }

  // CREDIT: https://stackoverflow.com/a/19533226/2683059
  protected function toSnakeCase($input) {
    return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
  }
}