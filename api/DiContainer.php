<?php


namespace HomeCEU\DTS\Api;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Render\TemplateCompiler;
use Psr\Container\ContainerInterface;

/**
 * Class DiContainer
 * @property Db\Config dbConfig
 * @property Db\Connection dbConnection
 * @property TemplateCompiler templateCompiler
 */
class DiContainer extends \Slim\Container implements ContainerInterface {
  public function __construct(array $values = []) {
    if ($values == []) {
      $values = include __DIR__."/services.php";
    }
    parent::__construct($values);
  }
}