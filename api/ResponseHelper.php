<?php


namespace HomeCEU\DTS\Api;


use HomeCEU\DTS\Entity\DocData;
use HomeCEU\DTS\Entity\Template;

class ResponseHelper {

  public static function templateDetailModel(Template $t): array {
    return [
        'templateId' => $t->templateId,
        'docType' => $t->docType,
        'templateKey' => $t->templateKey,
        'author' => $t->author,
        'createdAt' => $t->createdAt->format(\DateTime::W3C),
        'bodyUri' => "/template/{$t->templateId}"
    ];
  }

  public static function docDataDetailModel(DocData $d): array {
    return [
        'dataId' => $d->dataId,
        'docType' => $d->docType,
        'dataKey' => $d->dataKey,
        "createdAt" => $d->createdAt->format(\DateTime::W3C),
        "link" => "/docdata/{$d->dataId}"
    ];
  }

  public static function docDataModel(DocData $d) {
    return [
        'dataId' => $d->dataId,
        'docType' => $d->docType,
        'dataKey' => $d->dataKey,
        "createdAt" => $d->createdAt->format(\DateTime::W3C),
        "data" => $d->data
    ];
  }
}