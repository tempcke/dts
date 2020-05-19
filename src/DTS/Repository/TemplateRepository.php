<?php


namespace HomeCEU\DTS\Repository;


use HomeCEU\DTS\Entity\CompiledTemplate;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence;

class TemplateRepository {
  /** @var Persistence */
  private $persistence;

  /** @var RepoHelper */
  private $repoHelper;
  private $compiledTemplatePersistence;

  public function __construct(
      Persistence $persistence,
      Persistence $compiledTemplatePersistence
  ) {
    $this->persistence = $persistence;
    $this->compiledTemplatePersistence = $compiledTemplatePersistence;

    $this->repoHelper = new RepoHelper($persistence);
  }

  public function save(Template $template) {
    $this->persistence->persist($template->toArray());
  }

  public function getTemplateById(string $id) {
    $array = $this->persistence->retrieve($id);
    return Template::fromState($array);
  }

  public function getCompiledTemplateById(string $id) {
    $arr = $this->compiledTemplatePersistence->retrieve($id);
    return CompiledTemplate::fromState($arr);
  }

  public function findByDocType(string $docType) {
    return array_map(function($result) {
      return Template::fromState($result);
    }, $this->persistence->find(['docType' => $docType]));
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
