<?php

// this array gets passed into Slim\Container

return [
    'settings' => [
        'addContentLengthHeader' => false,
    ],
    'dbConfig' => function ($container) {
      return \HomeCEU\DTS\Db\Config::fromEnv();
    },
    'dbConnection' => function($container) {
      return \HomeCEU\DTS\Db::connection();
    },
    'templateCompiler' => function($container) {
      $compiler = \HomeCEU\DTS\Render\TemplateCompiler::create();
      $compiler->addHelper(\HomeCEU\DTS\Render\TemplateHelpers::ifComparisonHelper());

      return $compiler;
    }
];