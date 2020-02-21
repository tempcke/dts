<?php


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Db\Connection;
use HomeCEU\DTS\Persistence;

class TemplatePersistence extends AbstractPersistence implements Persistence {
  const TABLE = 'template';
  const ID_COL = 'template_id';

  private $map = [
      'templateId' => 'template_id',
      'docType' => 'doc_type',
      'templateKey' => 'template_key',
      'createdAt' => 'created_at'
  ];

  public function __construct(Connection $db) {
    parent::__construct($db);
    $this->useKeyMap($this->map);
  }

  public function delete($id) {
    $error = sprintf(
        "%s not implemented",
        __METHOD__
    );
    throw new \Exception($error);
  }
}