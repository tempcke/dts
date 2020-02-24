<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Render;


use HomeCEU\DTS\Render\CompilationException;
use HomeCEU\DTS\Render\Helper;
use HomeCEU\DTS\Render\Partial;
use HomeCEU\DTS\Render\TemplateCompiler as Template;

class TemplateCompilerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testCompileTemplate(): void
    {
        $data = ['placeholder' => 'password'];

        $template = Template::create('{{ placeholder }}')
                             ->compile();

        $this->assertEquals($data['placeholder'], $this->render($template, $data));
    }

    public function testCompileMissingPartial(): void
    {
        $this->expectException(CompilationException::class);

        Template::create('{{> expected_partial }}')
                ->compile();
    }

    public function testCompileWithPartial(): void
    {
        $template = '{{> expected_partial }}';

        $template = Template::create($template)
                             ->withPartials([new Partial('expected_partial', 'text')])
                             ->compile();

        $this->assertEquals('text', $this->render($template));
    }

    public function testCompileWithHelper(): void
    {
        $helper = new Helper('upper', function ($val) {
            return strtoupper($val);
        });

        $template = Template::create('{{upper string}}')
                             ->withHelpers([$helper])
                             ->compile();

        $this->assertEquals('TEXT', $this->render($template, ['string' => 'text']));
    }
}
