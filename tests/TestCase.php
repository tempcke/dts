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

  protected function fakeTemplateArray($docType = null, $key = null) {
    return [
        'templateId' => self::faker()->uuid,
        'docType' => $docType ?: __FUNCTION__,
        'templateKey' => $key ?: uniqid(),
        'name' => self::faker()->monthName,
        'author' => self::faker()->name,
        'createdAt' => new \DateTime('yesterday'),
        'body' => 'hi {{name}}'
    ];
  }
}