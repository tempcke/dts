<?php


namespace HomeCEU\DTS\Api;


use Slim\Container;

class App extends \Slim\App {
  public $routesFile = APP_ROOT.'/api/routes.php';
  public $servicesFile = APP_ROOT.'/api/services.php';

  public function __construct() {
    parent::__construct($this->_diContainer());
    $this->loadRoutes(...$this->_routes());
  }

  private function _diContainer() {
    $services = include $this->servicesFile;
    return new Container($services);
  }

  private function _routes() {
    return include $this->routesFile;
  }

  private function loadRoutes(Route ...$routes) {
    foreach ($routes as $route) {
      $methods = [strtoupper($route->method)];
      $this->map($methods, $route->uri, $route->function);
    }
  }
}