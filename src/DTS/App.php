<?php


namespace HomeCEU\DTS;


use HomeCEU\DTS\Api\Route;

class App extends \Slim\App {
  public $configFile = APP_ROOT.'/api/routes.php';

  public function __construct($container = []) {
    parent::__construct($container);

    $routes = include $this->configFile;
    $this->loadRoutes(...$routes);
  }

  public function loadRoutes(Route ...$routes) {
    foreach ($routes as $route) {
      $methods = [strtoupper($route->method)];
      $this->map($methods, $route->uri, $route->function);
    }
  }
}