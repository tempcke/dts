<?php


namespace HomeCEU\Tests\Api;

use PHPUnit\Framework\Assert;

class RenderTest extends TestCase {
  public function setUp(): void {
    parent::setUp();
  }

  public function testRenderFromKeys() {
    $templateKey = __FUNCTION__;
    $dataKey = __FUNCTION__;
    $this->addDocDataFixture($dataKey);
    $this->addTemplateFixture($templateKey);
    $response = $this->get("/render?docType={$this->docType}&templateKey={$templateKey}&dataKey={$dataKey}");
    Assert::assertEquals(200, $response->getStatusCode());
    Assert::assertEquals('Hi Fred', strval($response->getBody()));
  }
}