<?php declare(strict_types=1);


namespace HomeCEU\Tests\Api;


class StatusTest extends TestCase {
  public function testStatusReturnsOk(): void {
    $response = $this->get('/status');
    $this->assertStatus(200, $response);
  }
}
