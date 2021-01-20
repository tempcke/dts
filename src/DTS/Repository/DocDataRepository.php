<?php

namespace HomeCEU\DTS\Repository;

use DateTime;
use HomeCEU\DTS\Entity\DocData;
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

  public function save(DocData $docData): void {
    $this->persistence->persist($docData->toArray());
  }

  public function getByDocDataId($dataId): DocData {
    return DocData::fromState($this->persistence->retrieve($dataId));
  }

  public function newDocData(string $type, string $key, $data): DocData {
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

  public function allVersions(string $docType, string $dataKey): ?array {
    $filter = [
        'docType' => $docType,
        'dataKey' => $dataKey
    ];
    $cols = [
        'dataId', 'docType', 'dataKey', 'createdAt'
    ];
    $rows = $this->persistence->find($filter, $cols);
    return $this->toDocDataArray($rows);
  }

  public function lookupId(string $docType, string $dataKey): string {
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



  /**
   * @param array $rows
   * @return array|DocData[]
   */
  private function toDocDataArray(array $rows) {
    return array_map(function ($row) {
      return DocData::fromState($row);
    }, $rows);
  }
}
