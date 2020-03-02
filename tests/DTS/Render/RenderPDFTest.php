<?php declare(strict_types=1);


namespace DTS\Render;


use HomeCEU\Tests\DTS\Render\TestCase;
use PHPUnit\Framework\Assert;

class RenderPDFTest extends TestCase {
  public function testCreatePDF(): void {
    $data = ['name' => 'Peter Parker'];
    $template = $this->compile('{{ name }}');

    $path = $this->renderPDF($template, $data);
    Assert::assertFileExists($path);
    Assert::assertEquals('pdf', pathinfo($path)['extension']);
    unlink($path);
  }
}
