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
    $template = $this->repository->createNewTemplate($request->type, $request->key, $request->author, $request->body);
    $this->repository->save($template);
    $this->addCompiled($template);

    return $template;
  }

  private function addCompiled(Template $template): void {
    $partials = $this->repository->findPartialsByDocType($template->docType);
    $images = $this->repository->findImagesByDocType($template->docType);

    $this->compiler->setPartials(array_merge($partials, $images));
    $this->repository->addCompiled($template, $this->compiler->compile($template->body));
  }
}
