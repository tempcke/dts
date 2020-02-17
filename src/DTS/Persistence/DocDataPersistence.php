<?php


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Db\Connection;
use HomeCEU\DTS\Persistence;
use Ramsey\Uuid\Uuid;

class DocDataPersistence implements Persistence {
  const TABLE_DOCDATA = 'docdata';

  /** @var Connection */
  private $db;

  public function __construct(Connection $db) {
    $this->db = $db;
  }

  public function generateId() {
    // TODO: Implement persist() method.
  }

  public function persist($data) {
    $fixedData = $this->fixInputData($data);
    $fixedData['created_at'] = $this->isoDateToMySql($fixedData['created_at']);
    $this->db->insert(static::TABLE_DOCDATA, $fixedData);
  }

  public function retrieve($id) {
    // TODO: Implement persist() method.
  }

  public function delete($id) {
    // TODO: Implement delete() method.
  }

  public function find(array $filter) {
    // TODO: Implement find() method.
  }

  protected function isoDateToMySql($isoDate) {
    $date = new \DateTime($isoDate);
    return $date->format("Y-m-d H:i:s");
  }

  protected function fixInputData(array $input): array {
    $output = [];
    foreach ($input as $k=> $v) {
      $key = $this->toSnakeCase($k);
      $value = is_array($v) ? json_encode($v) : $v;
      $output[$key] = $value;
    }
    return $output;
  }

  // CREDIT: https://stackoverflow.com/a/19533226/2683059
  protected function toSnakeCase($input) {
    return ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $input)), '_');
  }
}