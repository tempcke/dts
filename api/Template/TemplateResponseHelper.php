<?php


namespace HomeCEU\DTS\Api\Template;

use DateTime;
use HomeCEU\DTS\Entity\Template;

class TemplateResponseHelper {

  public static function templateDetailModel(Template $t): array {
    return [
        'templateId' => $t->templateId,
        'docType' => $t->docType,
        'templateKey' => $t->templateKey,
        'author' => $t->author,
        'createdAt' => $t->createdAt->format(DateTime::W3C),
        'bodyUri' => "/template/{$t->templateId}"
    ];
  }
}