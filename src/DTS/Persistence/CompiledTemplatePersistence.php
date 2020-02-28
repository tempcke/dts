<?php declare(strict_types=1);


namespace HomeCEU\DTS\Persistence;


use HomeCEU\DTS\Db\Connection;

class CompiledTemplatePersistence extends AbstractPersistence {
  const TABLE = 'compiled_template';
  const ID_COL = 'template_id';

  private const MAP = [
      'templateId' => 'template_id',
      'body' => 'body',
      'createdAt' => 'created_at',
  ];

  public function __construct(Connection $db) {
    parent::__construct($db);
    $this->useKeyMap(self::MAP);
  }

  public function generateId(): void {
    $this->notImplemented(__METHOD__);
  }

  public function delete($id): void {
    $this->notImplemented(__METHOD__);
  }

  protected function notImplemented($method): void {
    $error = sprintf(
        "%s not implemented",
        $method
    );
    throw new \Exception($error);
  }
}
