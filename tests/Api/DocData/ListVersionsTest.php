<?php


namespace HomeCEU\Tests\Api\DocData;

use PHPUnit\Framework\Assert;

class ListVersionsTest extends TestCase {

  public function testListVersions() {
    $dataKey = __FUNCTION__; // just in case it doesnt cleanup, you will know where it came from.
    $this->addFixtureData($dataKey);

    $uri = "/docdata/{$this->docType}/{$dataKey}/history";
    $response = $this->get($uri);
    $responseData = json_decode(strval($response->getBody()), true);

    Assert::assertSame($response->getStatusCode(), 200);
    $this->assertTotalItems($responseData, 2);
    $this->AssertExpectedVersionItemKeys(
        $responseData,
        ['dataId', 'docType', 'dataKey', 'createdAt']
    );
  }


  /**
   * @param string $dataKey
   */
  private function addFixtureData(string $dataKey): void {
    $this->addDocDataFixture($dataKey);
    $this->addDocDataFixture(uniqid());
    $this->addDocDataFixture($dataKey);
  }

  /**
   * @param $responseData
   * @param array $expectedResponseKeys
   */
  private function AssertExpectedVersionItemKeys($responseData, array $expectedResponseKeys): void {
    foreach ($expectedResponseKeys as $key) {
      Assert::assertFalse(empty($responseData['items'][0][$key]));
    }

    Assert::assertFalse(
        array_key_exists('data', $responseData['items'][0]),
        "ERROR: get docdata history should not respond with the data"
    );
  }

  /**
   * @param $responseData
   */
  private function assertTotalItems($responseData, $total): void {
    Assert::assertCount($total, $responseData['items']);
    Assert::assertEquals($total, $responseData['total']);
  }
}