<?php declare(strict_types=1);


namespace Api;


use HomeCEU\Tests\Api\TestCase;
use PHPUnit\Framework\Assert;

class StatusTest extends TestCase {
  public function testStatusReturnsOk(): void {
    $response = $this->get('/status');
    Assert::assertEquals(200, $response->getStatusCode());
  }
}
