<?php


namespace HomeCEU\Tests\Api\DocData;

use DateTime;
use PHPUnit\Framework\Assert;

class GetDocDataTest extends TestCase {
  /**
   * @var array
   */
  private $fixtureData;
  /**
   * @var array
   */
  private $expectedExampleResponse;

  protected function setUp(): void {
    parent::setUp();
    $this->loadFixtureData();
    // parent is handling db transaction...
  }

  protected function tearDown(): void {
    parent::tearDown();
    // parent is handling db transaction rollback...
  }

  public function testGetById() {
    $dataId = $this->fixtureData['example']['dataId'];
    $responseData = $this->httpGet("/docdata/{$dataId}");
    Assert::assertEquals($this->expectedExampleResponse, $responseData);
  }

  protected function httpGet($uri) {
    $response = $this->get($uri);
    $responseData = json_decode($this->get($uri)->getBody(), true);
    $this->assertStatus(200, $response);
    $this->assertContentType('application/json', $response);
    return $responseData;
  }

  protected function loadFixtureData() {
    $day = 0;
    $exampleId = self::faker()->uuid;
    $this->fixtureData = [
        'A1' => [
            'dataId' => self::faker()->uuid,
            'docType' => $this->docType,
            'dataKey' => 'A',
            'createdAt' => new DateTime('2020-01-0'.++$day)
        ],
        'A2' => [
            'dataId' => self::faker()->uuid,
            'docType' => $this->docType,
            'dataKey' => 'A',
            'createdAt' => new DateTime('2020-01-0'.++$day)
        ],
        'find' => $this->docDataArray([
            'dataId' => self::faker()->uuid,
            'docType' => $this->docType
        ]),
        'example' => [
            'dataId' => $exampleId,
            'docType' => $this->docType,
            'dataKey' => 'example',
            "createdAt" => new DateTime("2020-10-13 23:47:07"),
            'data' => ['name'=>'joe']
        ]
    ];

    $this->expectedExampleResponse = [
        'dataId' => $exampleId,
        'docType' => $this->docType,
        'dataKey' => 'example',
        "createdAt" => "2020-10-13T23:47:07+00:00",
        'data' => ['name'=>'joe']
    ];

    foreach ($this->fixtureData as $r) {
      $this->docDataPersistence()->persist($this->docDataArray($r));
    }
  }
}