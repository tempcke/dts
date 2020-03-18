<?php


namespace HomeCEU\Tests\Api\DocData;

use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class DocDataAddTest extends TestCase {

  const EXPECTED_KEYS = ['dataId', 'docType', 'dataKey', 'createdAt'];

  public function testPostNewDocData(): void {
    $response = $this->post(
        '/docdata',
        $this->makeRequestArray($this->docType, __FUNCTION__, ['someid'=>uniqid()])
    );
    $this->assertStatus(201, $response);
    $this->assertContentType($response, 'application/json');
    $responseData = json_decode($response->getBody(), true);

    foreach (self::EXPECTED_KEYS as $key) {
      Assert::assertNotEmpty($responseData[$key]);
    }
    Assert::assertArrayNotHasKey('data', $responseData, "ERROR: post /docdata should not respond with the data");
  }

  /**
   * @dataProvider invalidDataProvider
   */
  public function testPostNewDocDataInvalidData($type, $key): void {
    $requestArray = $this->makeRequestArray($type, $key, '');
    $response = $this->post('/docdata', $requestArray);
    echo $response->getBody();
    $this->assertStatus(409, $response);
  }

  public function invalidDataProvider(): \Generator {
    yield [$this->docType, null];
    yield [$this->docType, ''];
    yield [null, uniqid()];
    yield ['', uniqid()];
  }

  protected function makeRequestArray($type, $key, $data): array {
    return [
        'docType' => $type,
        'dataKey' => $key,
        'data' => $data
    ];
  }
}
