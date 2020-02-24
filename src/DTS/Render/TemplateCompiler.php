<?php declare(strict_types=1);


namespace HomeCEU\DTS\Render;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class TemplateCompiler
{
    private $template;

    private $flags = Flags::FLAG_HANDLEBARS;
    private $options = [];
    private $helpers = [];
    private $partials;

    protected function __construct(string $template)
    {
        $this->template = $template;
    }

    public static function create(string $template): self
    {
        return new self($template);
    }

    public function addHelper(Helper $helper): void
    {
        $this->helpers[$helper->name] = $helper->func;
    }

    public function withHelpers(array $helpers): self
    {
        foreach ($helpers as $helper) {
            $this->addHelper($helper);
        }
        return $this;
    }

    public function addPartial(Partial $partial): void
    {
        $this->partials[$partial->name] = $partial->template;
    }

    public function withPartials(array $partials): self
    {
        foreach ($partials as $partial) {
            $this->addPartial($partial);
        }
        return $this;
    }

    public function compile(): string
    {
        try {
            $options = [
                'flags' => $this->flags | Flags::FLAG_ERROR_EXCEPTION,
                'helpers' => $this->helpers,
                'partials' => $this->partials,
            ];
            return LightnCandy::compile($this->template, $options);
        } catch (\Exception $e) {
            throw new CompilationException("Cannot compile template: {$e->getMessage()}");
        }
    }
}
