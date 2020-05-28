<?php declare(strict_types=1);


namespace HomeCEU\Tests\Api;


use HomeCEU\DTS\Api\ApiHelper;
use PHPUnit\Framework\Assert;

class ApiHelperTest extends TestCase {
  const HOST = 'example.com:8080';
  const SCHEME_HTTP = 'http';
  const BASE_URL = self::SCHEME_HTTP . '://' . self::HOST;

  protected function setUp(): void {
    parent::setUp();

    $_SERVER['HTTP_HOST'] = self::HOST;
    $_SERVER['REQUEST_SCHEME'] = self::SCHEME_HTTP;
  }

  public function testGetBaseURL(): void {
    Assert::assertEquals(self::BASE_URL, ApiHelper::getBaseURL());
  }

  public function testBuildUrl(): void {
    $route = '/endpoint/123';
    Assert::assertEquals(self::BASE_URL . $route, ApiHelper::buildUrl($route));
  }
}
