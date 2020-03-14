<?php


namespace HomeCEU\Tests\Api\DocData;

use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class ExistsTest extends TestCase {

  public function testHasData() {
    $dataKey = __FUNCTION__;
    $this->addDocDataFixture($dataKey);
    $response = $this->head("/docdata/{$this->docType}/{$dataKey}");
    Assert::assertEquals(200, $response->getStatusCode());
  }

  public function testDoesntHaveData() {
    $dataKey = __FUNCTION__;
    $response = $this->head("/docdata/{$this->docType}/{$dataKey}");
    Assert::assertEquals(404, $response->getStatusCode());
  }
}
