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
    $this->checkRequiredKeys($type, $key);
    $docData = $this->repo->newDocData($type, $key, $data);
    $this->repo->save($docData);
    return $docData->toArray();
  }

  private function checkRequiredKeys($type, $key) {
    if (empty($type))
      throw new InvalidDocDataAddRequestException("'docType' is missing or empty.");

    if (empty($key))
      throw new InvalidDocDataAddRequestException("'dataKey' is missing or empty.");
  }
}
