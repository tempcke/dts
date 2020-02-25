<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\Renderer;
use HomeCEU\Tests\DTS\TestCase as dtsTestCase;

class TestCase extends dtsTestCase {
  protected $renderer;

  protected function setUp(): void {
    $this->renderer = Renderer::create();
  }

  protected function render($compiledTemplate, $data = []): string {
    return $this->renderer->render($compiledTemplate, $data);
  }
}
