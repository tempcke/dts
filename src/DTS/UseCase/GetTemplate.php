<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Repository\TemplateRepository;

class GetTemplate {
  private $repository;

  public function __construct(TemplateRepository $repository) {
    $this->repository = $repository;
  }

  public function getTemplate(GetTemplateRequest $request) {
    if (!$request->isValid()) {
      throw new InvalidGetTemplateRequestException();
    }
    return $this->repository->findByDocType($request->type);
  }
}
