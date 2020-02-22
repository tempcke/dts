<?php


namespace HomeCEU\DTS\Api;


use Psr\Container\ContainerInterface;

class App extends \Slim\App {
  public $routesFile = APP_ROOT.'/api/routes.php';
  public $servicesFile = APP_ROOT.'/api/services.php';

  public function __construct(ContainerInterface $diContainer=null) {
    parent::__construct($diContainer ?: $this->_diContainer());
    $this->loadRoutes(...$this->_routes());
  }

  private function _diContainer() {
    return new DiContainer();
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