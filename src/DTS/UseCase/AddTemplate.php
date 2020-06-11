<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Repository\TemplateRepository;

class AddTemplate {
  private $repository;

  public function __construct(TemplateRepository $repository) {
    $this->repository = $repository;
  }

  public function addTemplate(AddTemplateRequest $request): Template {
    if (!$request->isValid()) {
      throw new InvalidTemplateAddRequestException();
    }
    $t = $this->repository->createNewTemplate($request->type, $request->key, $request->author, $request->body);
    $this->repository->save($t);

    return $t;
  }
}
