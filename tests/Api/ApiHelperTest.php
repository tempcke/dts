<?php declare(strict_types=1);


namespace HomeCEU\Tests\Api;


use HomeCEU\DTS\Api\ApiHelper;
use PHPUnit\Framework\Assert;

class ApiHelperTest extends TestCase {
  const HOST = 'example.com:8080';
  const SCHEME_HTTP = 'http';
  const SCHEME_HTTPS = 'https';
  const BASE_HTTP_URL = self::SCHEME_HTTP . '://' . self::HOST;
  const BASE_HTTPS_URL = self::SCHEME_HTTPS . '://' . self::HOST;

  protected function setUp(): void {
    parent::setUp();

    $_SERVER['HTTP_HOST'] = self::HOST;
    $_SERVER['REQUEST_SCHEME'] = self::SCHEME_HTTP;
  }

  public function testGetBaseURL(): void {
    Assert::assertEquals(self::BASE_HTTP_URL, ApiHelper::getBaseURL());
  }

  public function testGetBaseUrlDefaultToHttps(): void {
    $_SERVER['REQUEST_SCHEME'] = null;
    Assert::assertEquals(self::BASE_HTTPS_URL, ApiHelper::getBaseURL());
  }

  public function testBuildUrl(): void {
    $route = '/endpoint/123';
    Assert::assertEquals(self::BASE_HTTP_URL . $route, ApiHelper::buildUrl($route));
  }
}
