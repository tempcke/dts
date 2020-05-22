<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\RenderPDF;
use PHPUnit\Framework\Assert;

class RenderPDFTest extends TestCase {
  public function testCreatePDF(): void {
    $renderer = RenderPDF::create();
    $data = ['name' => 'Peter Parker'];
    $template = $this->compile('{{ name }}');

    $path = $renderer->render($template, $data);
    Assert::assertFileExists($path);
    Assert::assertEquals('pdf', pathinfo($path)['extension']);
    unlink($path);
  }
}
