<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Repository\TemplateRepository;

class GetTemplate {
  private $repository;

  public function __construct(TemplateRepository $repository) {
    $this->repository = $repository;
  }

  public function findTemplates(FindTemplateRequest $request): array {
    if (!$request->isValid()) {
      throw new InvalidGetTemplateRequestException();
    }
    if (!empty($request->key)) {
      return [$this->repository->getTemplateByKey($request->type, $request->key)];
    }
    return $this->repository->findByDocType($request->type);
  }

  public function getTemplateById(GetTemplateRequest $request): Template {
    return $this->repository->getTemplateById($request->templateId);
  }
}
