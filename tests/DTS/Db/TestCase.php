<?php


namespace HomeCEU\Tests\DTS\Db;


class TestCase extends \HomeCEU\Tests\DTS\TestCase {

  protected $dbEnv   = array();
  protected $envKeys = [
      'DB_DRIVER',
      'DB_HOST',
      'DB_USER',
      'DB_PASS',
      'DB_NAME',
      'DB_PORT'
  ];

  public function setUp(): void {
    parent::setUp();
    $this->saveDbEnv();
  }
  public function tearDown(): void {
    parent::tearDown();
    $this->restoreDbEnv();
  }

  protected function saveDbEnv() {
    foreach ($this->envKeys as $k) {
      $this->dbEnv[$k] = getenv($k);
    }
  }

  protected function restoreDbEnv() {
    foreach ($this->dbEnv as $k=>$v) {
      if ($v === false) $v = '';
      putenv("{$k}={$v}");
    }
  }
}