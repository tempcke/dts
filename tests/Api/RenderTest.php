<?php


namespace HomeCEU\Tests\Api;

use PHPUnit\Framework\Assert;
use Slim\Http\Stream;

class RenderTest extends TestCase {
  protected function setUp(): void {
    parent::setUp();
  }

  public function testTemplateNotFound(): void {
    $templateKey = __FUNCTION__;
    $dataKey = __FUNCTION__;

    $this->assertStatus(404, $this->get("/render/{$this->docType}/{$templateKey}/{$dataKey}"));
  }

  public function testRenderFromKeys(): void {
    $templateKey = __FUNCTION__;
    $dataKey = __FUNCTION__;
    $this->addDocDataFixture($dataKey);
    $this->addTemplateFixture($templateKey);
    $response = $this->get("/render/{$this->docType}/{$templateKey}/{$dataKey}");

    $this->assertStatus(200, $response);
    $this->assertContentType('text/html', $response);
    $this->assertEquals("Hi Fred", (string) $response->getBody());
  }
}
