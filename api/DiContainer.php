<?php


namespace HomeCEU\DTS\Api;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Render\TemplateCompiler;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Slim\Container;

/**
 * Class DiContainer
 * @property Db\Config dbConfig
 * @property Db\Connection dbConnection
 * @property TemplateCompiler templateCompiler
 * @property Logger logger
 */
class DiContainer extends Container implements ContainerInterface {
  public function __construct(array $values = []) {
    if ($values == []) {
      $values = include __DIR__."/services.php";
    }
    parent::__construct($values);
  }
}