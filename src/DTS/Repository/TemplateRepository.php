<?php


namespace HomeCEU\DTS\Repository;


use HomeCEU\DTS\Entity\CompiledTemplate;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Render\Partial;

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
    $templates = $this->persistence->find(['docType' => $docType]);

    return array_map(function ($key) use ($docType) {
      return $this->getTemplateByKey($docType, $key);
    }, $this->repoHelper->extractUniqueProperty($templates, 'templateKey'));
  }

  public function findPartialsByDocType(string $docType): array {
    return array_map(function ($partial) {
      return new Partial($partial->name, $partial->body);
    }, $this->findByDocType($docType . '/partial'));
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
