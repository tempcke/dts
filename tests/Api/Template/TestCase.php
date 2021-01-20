<?php


namespace HomeCEU\Tests\Api\Template;


use PHPUnit\Framework\Assert;

class TestCase extends \HomeCEU\Tests\Api\TestCase {


  protected function httpGetTemplatesFromUri($uri) {
    $response = $this->get($uri);
    $responseData = json_decode($response->getBody(), true);

    $this->assertStatus(200, $response);
    $this->assertContentType('application/json', $response);

    Assert::assertArrayHasKey('total', $responseData);
    Assert::assertArrayHasKey('items', $responseData);
    Assert::assertIsArray($responseData['items']);

    Assert::assertCount($responseData['total'], $responseData['items']);
    return $responseData;
  }

}