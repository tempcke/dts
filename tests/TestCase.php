<?php
namespace HomeCEU\Tests;

class TestCase extends \PHPUnit\Framework\TestCase {
  public static function faker() {
    return Faker::generator();
  }
}