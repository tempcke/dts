<?php

namespace HomeCEU\DTS\Repository;

use DateTime;
use HomeCEU\DTS\Entity\DocData;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence;

class DocDataRepository {
  /** @var Persistence */
  private $persistence;

  /** @var RepoHelper */
  private $repoHelper;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
    $this->repoHelper = new RepoHelper($persistence);
  }

  public function save(DocData $docData) {
    $this->persistence->persist($docData->toArray());
  }

  public function getByDocDataId($dataId) {
    return DocData::fromState($this->persistence->retrieve($dataId));
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

  public function allVersions(string $docType, string $dataKey) {
    $filter = [
        'docType' => $docType,
        'dataKey' => $dataKey
    ];
    $cols = [
        'dataId', 'docType', 'dataKey', 'createdAt'
    ];
    return $this->persistence->find($filter, $cols);
  }

  public function lookupId(string $docType, string $dataKey) {
    $filter = [
        'docType' => $docType,
        'dataKey' => $dataKey
    ];
    $cols = [
        'dataId',
        'createdAt'
    ];
    $row = $this->repoHelper->findNewest($filter, $cols);
    return $row['dataId'];
  }
}