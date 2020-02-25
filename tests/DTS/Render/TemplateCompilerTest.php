<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\CompilationException;
use HomeCEU\DTS\Render\Helper;
use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler;

class TemplateCompilerTest extends TestCase {
  private $compiler;

  protected function setUp(): void {
    parent::setUp();
    $this->compiler = TemplateCompiler::create();
    $this->compiler->setPartials([]);
    $this->compiler->setHelpers([]);
  }

  public function testCompileTemplate(): void {
    $data = ['placeholder' => 'password'];
    $template = $this->compile('{{ placeholder }}');

    $this->assertEquals($data['placeholder'], $this->render($template, $data));
  }

  public function testCompileMissingPartial(): void {
    $this->expectException(CompilationException::class);
    $this->compiler->compile('{{> expected_partial }}');
  }

  public function testCompileWithPartial(): void {
    $this->compiler->setPartials([new Partial('expected_partial', 'text')]);
    $this->assertEquals('text', $this->render($this->compile('{{> expected_partial }}')));
  }

  public function testCompileWithHelper(): void {
    $this->compiler->setHelpers([
        new Helper('upper', function ($val) {
          return strtoupper($val);
        })
    ]);
    $this->assertEquals('TEXT', $this->render($this->compile('{{upper var}}'), ['var' => 'text']));
  }

  private function compile($template): string {
    return $this->compiler->compile($template);
  }
}
