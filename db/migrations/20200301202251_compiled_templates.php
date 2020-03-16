<?php

use Phinx\Migration\AbstractMigration;

class CompiledTemplates extends AbstractMigration {
  public function up() {
    $sqlFile = __DIR__ . '/../sql/table_compiled_template.sql';
    $sql = file_get_contents($sqlFile);
    $this->execute($sql);
  }

  public function down() {
    $this->table('compiled_template')->drop()->save();
  }
}
