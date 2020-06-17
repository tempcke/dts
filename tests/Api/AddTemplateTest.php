<?php declare(strict_types=1);


namespace HomeCEU\Tests\Api;


class AddTemplateTest extends TestCase {
  public function testBadRequest(): void {
    $request = [
        'docType' => 'DT',
        'templateKey' => 'TK',
        'author' => 'Test Author',
        'body' => 'Hello, {{ name }}!'
    ];
    $response = $this->post('/template', $request);
    $this->assertStatus(400, $response);
  }
}
