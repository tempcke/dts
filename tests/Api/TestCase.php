<?php


namespace HomeCEU\Tests\Api;


use HomeCEU\DTS\Api\App;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Environment;
use Slim\Http\Request;

class TestCase extends \HomeCEU\Tests\TestCase {

  /** @var App */
  protected $app;

  public function setUp(): void {
    parent::setUp();
    $this->app = $this->slimApp();
  }

  protected function slimApp() {
    $app = new App([]);
    return $app;
  }


  protected function post($uri, array $data): ResponseInterface {
    $method = 'POST';
    $env = Environment::mock([
        'REQUEST_METHOD' => strtoupper($method),
        'REQUEST_URI'    => $uri,
        'CONTENT_TYPE'   => 'application/json'
    ]);
    $req = Request::createFromEnvironment($env)->withParsedBody($data);
    $this->app->getContainer()['request'] = $req;
    return $this->app->run(true);
  }

  protected function get($uri): ResponseInterface {
    $method = 'GET';
    $env = Environment::mock([
        'REQUEST_METHOD' => strtoupper($method),
        'REQUEST_URI'    => $uri,
        'CONTENT_TYPE'   => 'application/json'
    ]);
    $req = Request::createFromEnvironment($env);
    $this->app->getContainer()['request'] = $req;
    return $this->app->run(true);
  }

  protected function head($uri): ResponseInterface {
    $method = 'HEAD';
    $env = Environment::mock([
        'REQUEST_METHOD' => strtoupper($method),
        'REQUEST_URI'    => $uri
    ]);
    $req = Request::createFromEnvironment($env);
    $this->app->getContainer()['request'] = $req;
    return $this->app->run(true);
  }
}