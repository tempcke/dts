<?php
namespace HomeCEU\Tests;

class TestCase extends \PHPUnit\Framework\TestCase {
  public static function setUpBeforeClass(): void {
    parent::setUpBeforeClass();
    if (!defined('APP_ROOT')) {
      define('APP_ROOT', realpath(__DIR__.'/../'));
    }
  }

  public static function faker() {
    return Faker::generator();
  }
}