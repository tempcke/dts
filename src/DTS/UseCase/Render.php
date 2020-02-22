<?php


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;

class Render {

  /** @var DocDataRepository */
  private $docDataRepo;

  /** @var TemplateRepository  */
  private $templateRepo;

  public function __construct(TemplateRepository $templateRepo, DocDataRepository $docDataRepo) {
    $this->docDataRepo = $docDataRepo;
    $this->templateRepo = $templateRepo;
  }

  public function renderDoc(RenderRequest $request) {
    if (!$request->isValid()) {
      throw new InvalidRenderRequestException;
    }
  }
}