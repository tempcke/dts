<?php

namespace HomeCEU\Tests\DTS\Db;

use HomeCEU\DTS\Db\Config as DbConfig;
use PHPUnit\Framework\Assert;

class ConfigTest extends TestCase {

  public function testSqliteDefaults() {
    $c = DbConfig::sqlite();
    Assert::assertTrue($c->isSqlite());
    Assert::assertEquals('sqlite', $c->driver);
    Assert::assertEquals(':memory:', $c->location);
    Assert::assertEquals('sqlite::memory:',$c->dsn());
  }

  public function testSqlite() {
    $c = DbConfig::sqlite('loc');
    Assert::assertTrue($c->isSqlite());
    Assert::assertEquals('sqlite', $c->driver);
    Assert::assertEquals('loc', $c->location);
    Assert::assertEquals('sqlite:loc',$c->dsn());
  }

  public function testMysql() {
    $c = DbConfig::mysql('h','n','u','p',123);
    Assert::assertFalse($c->isSqlite());
    $this->assertDbConfigState($c);
  }

  public function testBuildFromEnv() {
    putenv('DB_HOST=h');
    putenv('DB_USER=u');
    putenv('DB_PASS=p');
    putenv('DB_NAME=n');
    putenv('DB_PORT=123');
    $c = DbConfig::fromEnv();
    $this->assertDbConfigState($c);
  }

  /**
   * @param $c
   */
  private function assertDbConfigState(DbConfig $c) {
    Assert::assertEquals('h', $c->host);
    Assert::assertEquals('n', $c->name);
    Assert::assertEquals('u', $c->user);
    Assert::assertEquals('p', $c->pass);
    Assert::assertEquals(123, $c->port);
    Assert::assertEquals('mysql', $c->driver);
    Assert::assertEquals('mysql:host=h;port=123;dbname=n', $c->dsn());
  }
}