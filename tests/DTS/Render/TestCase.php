<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\Renderer;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\Tests\DTS\TestCase as dtsTestCase;

class TestCase extends dtsTestCase {
  protected $renderer;
  protected $compiler;

  protected function setUp(): void {
    $this->renderer = Renderer::create();
    $this->compiler = TemplateCompiler::create();
  }

  protected function compile($template): string {
    return $this->compiler->compile($template);
  }

  protected function render($compiledTemplate, $data = []): string {
    return $this->renderer->render($compiledTemplate, $data);
  }

  public function pdf($compiledTemplate, $data = []) {
    return $this->renderer->pdf($compiledTemplate, $data);
  }
}
