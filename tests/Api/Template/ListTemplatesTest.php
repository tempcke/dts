<?php


namespace HomeCEU\Tests\Api\Template;


use HomeCEU\Tests\Api\TestCase;

class ListTemplatesTest extends TestCase {
  protected function setUp(): void {
    parent::setUp();
  }

  protected function tearDown(): void {
    parent::tearDown();
  }

  public function testFilteredBySearchString() {
    // Fixture Data
    $p = $this->templatePersistence();
    $count = 3;
    for ($i=1; $i<=$count; $i++) { // matching templates
      $p->persist(array_merge(
          $this->fakeTemplateArray(),
          [
              'docType' => 'cert',
              'templateKey' => 'to key or not to key '.$i,
              'name' => 'some cool name',
              'author' => 'uncle bob'
          ]
      ));
    }
    $p->persist($this->fakeTemplateArray());  // non matching template

    $response = $this->get("/template?filter[search]=not cool bob");
    $responseData = json_decode($response->getBody(), true);

    $this->assertContentType('application/json', $response);
    $this->assertStatus(200, $response);

    $this->assertArrayHasKey('total', $responseData);
    $this->assertEquals($count, $responseData['total']);

    $this->assertArrayHasKey('items', $responseData);
    $this->assertIsArray($responseData['items']);
    $this->assertCount($count, $responseData['items']);
  }
}