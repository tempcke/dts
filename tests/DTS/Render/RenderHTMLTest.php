<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\RenderHTML;
use PHPUnit\Framework\Assert;

class RenderHTMLTest extends TestCase {
  public function testCreateHTML(): void {
    $renderer = RenderHTML::create();
    $data = ['name' => 'Tester'];
    $template = $this->compile('{{ name }}');

    $path = $renderer->render($template, $data);
    Assert::assertFileExists($path);
    Assert::assertEquals('Tester', file_get_contents($path));
  }
}
