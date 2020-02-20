<?php


namespace HomeCEU\Tests\Api\DocData;


use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class ListVersionsTest extends TestCase {
  /** @var string */
  private $docType;

  /** @var DocDataPersistence */
  private $persistence;

  /** @var DiContainer */
  private $di;

  public function setUp(): void {
    parent::setUp();
    $this->docType = (new \ReflectionClass($this))->getShortName().'-'.time();
    $this->di = new DiContainer();
    $this->persistence = new DocDataPersistence($this->di->dbConnection);
  }

  public function tearDown(): void {
    $db = $this->di->dbConnection;
    $db->deleteWhere(
        DocDataPersistence::TABLE_DOCDATA,
        ['doc_type'=>$this->docType]
    );
    parent::tearDown();
  }

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

  protected function addFixture($dataKey) {
    $this->persistence->persist([
        'docType' => $this->docType,
        'dataKey' => $dataKey,
        'createdAt' => new \DateTime(),
        'dataId' => uniqid(),
        'data' => ['foo']
    ]);
  }

  /**
   * @param string $dataKey
   */
  private function addFixtureData(string $dataKey): void {
    $this->addFixture($dataKey);
    $this->addFixture(uniqid());
    $this->addFixture($dataKey);
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