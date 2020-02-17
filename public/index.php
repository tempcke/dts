<?php

use HomeCEU\DTS\Api\App;

define('APP_ROOT', realpath(__DIR__.'/../'));
init_composer();
runApp();

function runApp() {
  $app = new App();
  $app->run();
}


function init_composer() {
  $composerAutoloader = APP_ROOT.'/vendor/autoload.php';

  if (!file_exists($composerAutoloader)) {
    http_response_code(500);
    die('Please run `composer update` first');
  }

  require_once $composerAutoloader;
}

