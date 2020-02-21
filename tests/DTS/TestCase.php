<?php
namespace HomeCEU\Tests\DTS;

class TestCase extends \HomeCEU\Tests\TestCase {

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