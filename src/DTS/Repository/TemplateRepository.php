<?php


namespace HomeCEU\DTS\Repository;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Persistence;

class TemplateRepository {
  /** @var Persistence */
  private $persistence;

  public function __construct(Persistence $persistence) {
    $this->persistence = $persistence;
  }

  public function save(Template $template) {
    $this->persistence->persist($template->toArray());
  }

  public function getTemplateById(string $id) {
    $array = $this->persistence->retrieve($id);
    return Template::fromState($array);
  }

  public function getTemplateByKey(string $docType, string $key) {
    $p = $this->persistence;
    $rows = $p->find(['docType'=>$docType, 'templateKey'=>$key]);
    usort($rows, [$this, 'rowcomp']);
    $row = array_pop($rows);
    return Template::fromState($row);
  }

  public function lookupId($docType, $templateKey) {
    $filter = [
        'docType' => $docType,
        'templateKey' => $templateKey
    ];
    $cols = [
        'templateId'
    ];
    $rows = $this->persistence->find($filter, $cols);
    return $rows[0]['templateId'];
  }

  // used for usort - https://www.php.net/manual/en/function.usort.php
  protected function rowComp($a, $b) {
    $adate = $a['createdAt'];
    $bdate = $b['createdAt'];
    return $adate < $bdate ? -1: 1;
  }
}