<?php


namespace HomeCEU\Tests\Api\DocData;

use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class DocDataAddTest extends TestCase {

  public function setUp(): void {
    parent::setUp();
  }

  public function testBar() {
    $requestJson = '{"docType":"courseCompletionCertificate","dataKey":"ABC123","data":{"name":"Fred"}}';
    $data = json_decode($requestJson, true);
    $response = $this->post('/docdata', $data);

    Assert::assertSame($response->getStatusCode(), 200);
    $responseData = json_decode((string)$response->getBody(), true);
    $keys = ['dataId', 'docType', 'dataKey', 'createdAt'];
    foreach ($keys as $key) {
      Assert::assertFalse(empty($responseData[$key]));
    }
    Assert::assertFalse(
        array_key_exists('data', $responseData),
        "ERROR: post /docdata should not respond with the data"
    );
  }

}