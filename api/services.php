<?php

namespace HomeCEU\DTS\Api;

use HomeCEU\DTS\Db;
use HomeCEU\DTS\Db\Config;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Render\TemplateHelpers;

// this array gets passed into Slim\Container
return [
    'settings' => [
        'addContentLengthHeader' => false,
    ],
    'dbConfig' => function ($container) {
      return Config::fromEnv();
    },
    'dbConnection' => function($container) {
      return Db::connection();
    },
    'templateCompiler' => function($container) {
      $compiler = TemplateCompiler::create();
      $compiler->addHelper(TemplateHelpers::ifComparisonHelper());

      return $compiler;
    },
    'logger' => function($container) {
      return Logger::instance();
    }
];