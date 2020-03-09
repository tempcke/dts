<?php


namespace HomeCEU\DTS\Repository;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence;

class TemplateRepository {
  /** @var Persistence */
  private $persistence;

  /** @var RepoHelper */
  private $repoHelper;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
    $this->repoHelper = new RepoHelper($persistence);
  }

  public function save(Template $template) {
    $this->persistence->persist($template->toArray());
  }

  public function getTemplateById(string $id) {
    $array = $this->persistence->retrieve($id);
    return Template::fromState($array);
  }

  public function getTemplateByKey(string $docType, string $key) {
    $filter = [
        'docType' => $docType,
        'templateKey' => $key
    ];
    $row = $this->repoHelper->findNewest($filter);
    return Template::fromState($row);
  }

  public function lookupId($docType, $templateKey) {
    $filter = [
        'docType' => $docType,
        'templateKey' => $templateKey
    ];
    $cols = [
        'templateId',
        'createdAt'
    ];
    $row = $this->repoHelper->findNewest($filter, $cols);
    return $row['templateId'];
  }
}