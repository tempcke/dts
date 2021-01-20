<?php declare(strict_types=1);


namespace HomeCEU\Tests\Api;


class GetTemplateTest extends TestCase {
  protected function setUp(): void {
    parent::setUp();
  }

  protected function tearDown(): void {
    parent::tearDown();
  }

  public function testGetByIdNotFound(): void {
    $response = $this->get('/template/made-up-id');
    $this->assertStatus(404, $response);
  }

  public function testGetById(): void {
    $templateId = uniqid();
    $body = "Hello, World!";
    $this->addTemplateFixture(__FUNCTION__, $templateId, $body);

    $response = $this->get("/template/{$templateId}");
    $this->assertStatus(200, $response);
    $this->assertEquals($body, (string) $response->getBody());
  }

  public function testGetByTypeAndKeyNotFound(): void {
    $response = $this->get('/template/type/key');
    $this->assertStatus(404, $response);
  }

  public function testGetByTypeAndKey(): void {
    $templateId = uniqid();
    $body = "Hello, World!";
    $key = __FUNCTION__;
    $this->addTemplateFixture($key, $templateId, $body);

    $response = $this->get("/template/{$this->docType}/{$key}");
    $this->assertStatus(200, $response);
    $this->assertEquals($body, (string) $response->getBody());
  }
}
