<?php


namespace HomeCEU\DTS;

use HomeCEU\DTS\Db\Config as DbConfig;
use HomeCEU\DTS\Db\Connection;

class Db {

  /** @var Connection */
  private static $connection;

  public static function connection(): Connection {
    return self::$connection ?: self::$connection = self::newConnection();
  }

  public static function newConnection(array $options = null):  Connection {
    return Connection::buildFromConfig(static::dbConfig(), $options);
  }

  public static function dbConfig(): DbConfig {
    return DbConfig::fromEnv();
  }
}
