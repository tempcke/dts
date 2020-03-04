<?php


namespace HomeCEU\Tests\Api\DocData;

use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

class TestCase extends \HomeCEU\Tests\Api\TestCase {

  protected function setUp(): void {
    parent::setUp();
  }

  protected function assertContentType(ResponseInterface $response, $contentType): void {
    $headers = $response->getHeaders();

    Assert::assertTrue(
        in_array($contentType, $headers['Content-Type']),
        sprintf('Content-Type does not include "%s"', $contentType)
    );
  }
}
