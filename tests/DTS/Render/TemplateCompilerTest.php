<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\CompilationException;
use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler as Template;

class TemplateCompilerTest extends RenderTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCompileTemplate(): void
    {
        $template = "{{ placeholder }}";
        $data = ['placeholder' => 'password'];

        $cTemplate = Template::create($template)
                             ->compile();

        $rendered = $this->renderer->renderCompiledTemplate($cTemplate, $data);

        $this->assertEquals($data['placeholder'], $rendered);
    }

    public function testCompileMissingPartial(): void
    {
        $this->expectException(CompilationException::class);

        $template = "{{> expected_partial }}";

        Template::create($template)
                ->compile();
    }

    public function testCompileWithPartial(): void
    {
        $template = "{{> expected_partial }}";

        $cTemplate = Template::create($template)
                             ->withPartials([new Partial('expected_partial', 'text')])
                             ->compile();

        $this->assertEquals('text', $this->renderer->renderCompiledTemplate($cTemplate, []));
    }
}
