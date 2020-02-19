<?php


namespace HomeCEU\Tests\Api\DocData;

use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class DocDataAddTest extends TestCase {

  public function setUp(): void {
    parent::setUp();
  }

  public function testPostNewDocData() {
    $requestArray = [
        "docType" => (new \ReflectionClass($this))->getShortName(),
        "dataKey" => __FUNCTION__,
        "data" => ["someid"=>uniqid()]
    ];

    $response = $this->post('/docdata', $requestArray);
    $responseBody = strval($response->getBody());
    $responseData = json_decode($responseBody, true);

    $expectedResponseCode = 200;
    $expectedResponseKeys = ['dataId', 'docType', 'dataKey', 'createdAt'];

    Assert::assertSame($response->getStatusCode(), $expectedResponseCode);
    foreach ($expectedResponseKeys as $key) {
      Assert::assertFalse(empty($responseData[$key]));
    }

    Assert::assertFalse(
        array_key_exists('data', $responseData),
        "ERROR: post /docdata should not respond with the data"
    );
  }
}