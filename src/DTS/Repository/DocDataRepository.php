<?php

namespace HomeCEU\DTS\Repository;

use DateTime;
use HomeCEU\DTS\Entity\DocData;
use HomeCEU\DTS\Persistence;

class DocDataRepository {
  /** @var Persistence */
  private $persistence;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
  }

  public function save(DocData $docData) {
    $this->persistence->persist($docData->toArray());
  }

  public function newDocData($type, $key, $data) {
    return DocData::fromState(
        [
            'dataId' => $this->persistence->generateId(),
            'docType' => $type,
            'dataKey' => $key,
            'data' => $data,
            'createdAt' => (new DateTime())->format(DateTime::ISO8601)
        ]
    );
  }

  public function allVersions(string $key) {
    $filter = [
        'dataKey' => $key
    ];
    $cols = [
        'dataId', 'docType', 'dataKey', 'createdAt'
    ];
    return $this->persistence->find($filter, $cols);
  }
}