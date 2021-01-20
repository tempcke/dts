<?php


namespace HomeCEU\Tests\Api\Template;

use DateTime;
use PHPUnit\Framework\Assert;

class TemplateVersionsTest extends TestCase {

  protected $type = 'TemplateVersionsTest';
  protected $key;
  protected $data = [];
  protected $expectedResults = [];
  /**
   * @var array
   */
  private $expectedExampleResponse;
  /**
   * @var string
   */
  private $exampleKey;

  protected function setUp(): void {
    parent::setUp();
    $this->loadFixtureData();
    // parent is handling db transaction...
  }

  protected function tearDown(): void {
    parent::tearDown();
    // parent is handling db transaction rollback...
  }

  // test GET /template/{type}/{key}/history
  public function testGetTemplateHistory() {
    $data = $this->httpGetTemplatesFromUri("/template/{$this->type}/{$this->key}/history");
    $this->assertExpectedResults($data, "versions");
  }

  public function testTemplateResponseFormat() {
    $data = $this->httpGetTemplatesFromUri("/template/{$this->type}/{$this->exampleKey}/history");
    $actualStruct = $data['items'][0];
    $this->assertEquals($this->expectedExampleResponse, $actualStruct);
  }

  protected function assertExpectedResults($data, $key) {
    $expectedIds = [];
    foreach ($this->expectedResults[$key] as $k) {
      array_push($expectedIds, $this->data[$k]['templateId']);
    }

    Assert::assertSameSize($expectedIds, $data['items']);

    foreach ($data['items'] as $row) {
      Assert::assertContains($row['templateId'], $expectedIds);
      Assert::assertArrayNotHasKey('body', $row);
    }
  }

  protected function loadFixtureData() {
    $this->key = self::faker()->firstName;
    $exampleId = self::faker()->uuid;
    $this->exampleKey = "example-".uniqid();
    $this->data = [
        'v1' => [
            'templateId' => self::faker()->uuid,
            'docType' => $this->type,
            'templateKey' => $this->key,
            'name' => self::faker()->sentence,
            'author' => self::faker()->name,
            'createdAt' => new DateTime('2020-01-01'),
            'body' => 'hi {{name}}'
        ],
        'v2' => [
            'templateId' => self::faker()->uuid,
            'docType' => $this->type,
            'templateKey' => $this->key,
            'name' => self::faker()->sentence,
            'author' => self::faker()->name,
            'createdAt' => new DateTime('2020-01-02'),
            'body' => 'hi {{name}}'
        ],
        'v3' => [
            'templateId' => self::faker()->uuid,
            'docType' => $this->type,
            'templateKey' => $this->key,
            'name' => self::faker()->sentence,
            'author' => self::faker()->name,
            'createdAt' => new DateTime('2020-01-03'),
            'body' => 'hi {{name}}'
        ],
        'other' => [
            'templateId' => self::faker()->uuid,
            'docType' => $this->type,
            'templateKey' => self::faker()->lastName,
            'name' => self::faker()->sentence,
            'author' => self::faker()->name,
            'createdAt' => new DateTime('2020-01-04'),
            'body' => 'hi {{name}}'
        ],
        'example' => [
            "templateId" => $exampleId,
            "docType" => $this->type,
            "templateKey" => $this->exampleKey,
            "name" => "is this field even used?",
            "author" => "Robert Martin",
            "createdAt" => new DateTime("2020-10-13 23:47:07"),
            'body' => 'hi {{name}}!'
        ]
    ];

    $this->expectedExampleResponse = [
        "templateId" => $exampleId,
        "docType" => $this->type,
        "templateKey" => $this->exampleKey,
        "author" => "Robert Martin",
        "createdAt" => "2020-10-13T23:47:07+00:00",
        "bodyUri" => "/template/{$exampleId}"
    ];

    foreach ($this->data as $row) {
      $this->templatePersistence()->persist($row);
    }

    $this->expectedResults = [
        'versions' => [
            'v1','v2','v3'
        ],
    ];
  }
}