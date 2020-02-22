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
    $response = $this->get("/render?docType={$this->docType}&templateKey={$templateKey}&dataKey={$dataKey}");
    Assert::assertEquals(200, $response->getStatusCode());
    Assert::assertEquals('Hi Fred', strval($response->getBody()));
  }

  protected function loadDocDataFixture($dataKey) {
    $this->docDataPersistence()->persist([
        'docType' => $this->docType,
        'dataKey' => $dataKey,
        'createdAt' => $this->createdAtDateTime(),
        'dataId' => uniqid(),
        'data' => ['name'=>'Fred']
    ]);
  }
  protected function loadTemplateFixture($dataKey) {
    $this->TemplatePersistence()->persist([
        'docType' => $this->docType,
        'templateKey' => $dataKey,
        'createdAt' => $this->createdAtDateTime(),
        'templateId' => uniqid(),
        'name'=>'name',
        'author'=>'author',
        'body' => 'Hi {{name}}'
    ]);
  }
}