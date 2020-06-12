<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\TemplateRepository;

class AddTemplate {
  private $repository;
  private $compiler;

  public function __construct(TemplateRepository $repository) {
    $this->repository = $repository;
    $this->compiler = TemplateCompiler::create();
  }

  public function addTemplate(AddTemplateRequest $request): Template {
    $this->validateRequest($request);

    $template = $this->repository->createNewTemplate($request->type, $request->key, $request->author, $request->body);
    $this->repository->save($template);
    $this->addCompiled($template);

    return $template;
  }

  private function validateRequest(AddTemplateRequest $request): void {
    if (!$request->isValid()) {
      throw new InvalidTemplateAddRequestException();
    }
  }

  private function addCompiled(Template $template): void {
    $this->repository->addCompiled($template, $this->compiler->compile($template->body));
  }
}
