<?php


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Db\Connection;
use HomeCEU\DTS\Persistence;
use Ramsey\Uuid\Uuid;

class DocDataPersistence extends AbstractPersistence implements Persistence {
  const TABLE_DOCDATA = 'docdata';

  private $map = [
      'dataId' => 'data_id',
      'docType' => 'doc_type',
      'dataKey' => 'data_key',
      'createdAt' => 'created_at',
  //    'data' => 'data'
  ];

  /** @var Connection */
  private $db;

  public function __construct(Connection $db) {
    $this->db = $db;
    $this->useKeyMap($this->map);
  }

  public function generateId() {
    return Uuid::uuid1();
  }

  public function persist($entity) {
    $fixedData = $this->flatten($entity);
    $this->db->insert(static::TABLE_DOCDATA, $fixedData);
  }

  public function retrieve($id, array $cols=['*']) {
    $row = $this->db->selectWhere(
        static::TABLE_DOCDATA,
        $this->selectColumns(...$cols),
        ['data_id'=>$id]
    )->fetch();
    return $this->hydrate($row);
  }

  public function find(array $filter, $cols=['*']) {
    $where = $this->flatten($filter); // changes keys to snake_case
    $rows = $this->db->selectWhere(
        static::TABLE_DOCDATA,
        $this->selectColumns(...$cols),
        $where
    )->fetchAll();
    return array_map([$this, 'hydrate'], $rows);
  }

  protected function selectColumns(...$cols) {
    $selectedCols = [];
    foreach ($cols as $alias) {
      array_push($selectedCols, $this->dbKey($alias));
    }
    return implode(', ', $selectedCols);
  }

  public function delete($id) {
    $error = sprintf(
        "%s not implemented",
        __METHOD__
    );
    throw new \Exception($error);
  }
}