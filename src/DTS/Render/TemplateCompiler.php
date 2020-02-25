<?php declare(strict_types=1);


namespace HomeCEU\DTS\Render;


use LightnCandy\Flags;
use LightnCandy\LightnCandy;

class TemplateCompiler {
  private $flags = Flags::FLAG_HANDLEBARS;
  private $helpers = [];
  private $partials = [];

  public static function create(): self {
    return new self();
  }

  public function setHelpers(array $helpers): self {
    foreach ($helpers as $helper) {
      $this->addHelper($helper);
    }
    return $this;
  }

  protected function addHelper(Helper $helper): void {
    $this->helpers[$helper->name] = $helper->func;
  }

  public function setPartials(array $partials): self {
    foreach ($partials as $partial) {
      $this->addPartial($partial);
    }
    return $this;
  }

  protected function addPartial(Partial $partial): void {
    $this->partials[$partial->name] = $partial->template;
  }

  public function compile(string $template): string {
    try {
      $options = [
          'flags' => $this->flags | Flags::FLAG_ERROR_EXCEPTION,
          'helpers' => $this->helpers,
          'partials' => $this->partials,
      ];
      return LightnCandy::compile($template, $options);
    } catch (\Exception $e) {
      throw new CompilationException("Cannot compile template: {$e->getMessage()}");
    }
  }
}
