<?php


namespace HomeCEU\Tests\Api\DocData;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class DocDataAddTest extends TestCase {

  public function testPostNewDocData() {
    $requestArray = [
        "docType" => $this->docType,
        "dataKey" => __FUNCTION__,
        "data" => ["someid"=>uniqid()]
    ];

    $response = $this->post('/docdata', $requestArray);
    $this->assertContentType($response, 'application/json');
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

  protected function assertContentType(ResponseInterface $response, $contentType): void {
    $headers = $response->getHeaders();

    Assert::assertTrue(
        in_array($contentType, $headers['Content-Type']),
        'Content-Type does not include "application/json"'
    );
  }
}
