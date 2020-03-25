<?php

namespace HomeCEU\DTS\UseCase;

use HomeCEU\DTS\Repository\DocDataRepository;

class AddDocData {
  /** @var DocDataRepository */
  private $repo;

  public function __construct(DocDataRepository $repo) {
    $this->repo = $repo;
  }

  public function add($type, $key, $data): array {
    $docData = $this->repo->newDocData($type, $key, $data);
    $this->repo->save($docData);
    return $docData->toArray();
  }
}
