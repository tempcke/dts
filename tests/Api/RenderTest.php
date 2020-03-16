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

    $response = $this->get($this->buildURI($templateKey, $dataKey));
    Assert::assertEquals(404, $response->getStatusCode());
  }

  public function testRenderFromKeys(): void {
    $templateKey = __FUNCTION__;
    $dataKey = __FUNCTION__;
    $this->addDocDataFixture($dataKey);
    $this->addTemplateFixture($templateKey);
    $response = $this->get($this->buildURI($templateKey, $dataKey));

    $this->assertContentType($response, 'application/pdf');
    Assert::assertEquals(200, $response->getStatusCode());
    Assert::assertInstanceOf(Stream::class, $response->getBody());
  }

  private function buildURI(...$args)
  {
    return "/render/{$this->docType}/" . implode('/', $args);
  }
}
