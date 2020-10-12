<?php


namespace HomeCEU\DTS\UseCase;


use HomeCEU\DTS\Repository\TemplateRepository;

class ListTemplates {
  /** @var TemplateRepository */
  private $repo;

  public function __construct(TemplateRepository $repo) {
    $this->repo = $repo;
  }

  public function search(string $searchString) {
    return $this->repo->search($searchString);
  }
}