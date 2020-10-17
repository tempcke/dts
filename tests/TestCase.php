<?php
namespace HomeCEU\Tests;

use DateTime;
use HomeCEU\DTS\Entity\DocData;

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
        'createdAt' => new DateTime('yesterday'),
        'body' => 'hi {{name}}'
    ];
  }



  protected function newDocData(array $overwrite=[]): DocData {
    return DocData::fromState($this->docDataArray($overwrite));
  }

  protected function docDataArray(array $overwrite=[]) {
    $base = [
        'dataId'     => self::faker()->uuid,
        'docType'    => uniqid(__FUNCTION__),
        'dataKey'    => self::faker()->colorName,
        'createdAt'  => $this->createdAtDateTime(),
        'data'       => [
            "firstName" => self::faker()->firstName,
            "lastName"  => self::faker()->lastName,
            "email"     => self::faker()->email
        ]
    ];
    return array_merge($base, $overwrite);
  }

  protected function createdAtDateTime(): DateTime {
    static $date = '2000-01-01';
    $dt = new DateTime($date.' + 1day');
    $date = $dt->format('Y-m-d');
    return $dt;
  }
}