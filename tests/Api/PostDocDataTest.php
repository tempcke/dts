<?php


namespace HomeCEU\Tests\Api;


use GuzzleHttp\RequestOptions;
use HomeCEU\DTS\Api\PostDockData;
use HomeCEU\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Http\Factory\DecoratedResponseFactory;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;
use GuzzleHttp\Client as Guzzle;
use HomeCEU\DTS\Api\Route;

class PostDocDataTest extends TestCase {
  /** @var App */
  protected $app;

  public function setUp(): void {
    parent::setUp();
    $this->app = $this->slimApp();
  }

  //public function testFoo() {
  //  $r = $this->request('POST', '/docdata');
  //  $r->withParsedBody([]);
  //  $g = new Guzzle();
  //  $response = $g->request('POST', 'http://localhost:8080/docdata', [
  //      RequestOptions::JSON => [
  //          'docType' => 'courseCompletionCertificate',
  //          'dataKey' => uniqid(),
  //          ['name'=>'fred']
  //      ]
  //  ]);
  //  $body = $response->getBody()->getContents();
  //  var_export([
  //      'code'=>$response->getStatusCode(),
  //      'body'=>$body
  //  ]);
  //  Assert::assertTrue(false);
  //}

  public function testBar() {
    $requestJson = '{"docType":"courseCompletionCertificate","dataKey":"ABC123","data":{"name":"Fred"}}';
    $data = json_decode($requestJson, true);
    $request = $this->postRequest('/docdata', $data);

    // $this->app->getContainer()['request'] = $request;

    //var_dump(['method'=>$request->getMethod(), 'uri'=>$request->getUri()->getPath(), 'body'=>$request->getParsedBody()]);

    $this->app->run($request);
    $response = $this->app->response;
    var_dump($response);
    $this->assertSame($response->getStatusCode(), 200);
  }

  protected function slimApp() {
    $app = AppFactory::create();
    $routes = [
        new Route('POST', '/docdata', PostDockData::class . ':post')
    ];
    /** @var Route $route */
    foreach ($routes as $route) {
      $app->map([$route->method], $route->uri, $route->function);
    }

    return $app;
  }

  public function postRequest($uri, array $data) {
    $method = 'POST';
    $server = [
        'REQUEST_METHOD' => $method,
        'REQUEST_URI'    => $uri,
        'CONTENT_TYPE'   => 'application/json'
    ];

    foreach ($server as $k=>$v) {
      $_SERVER[$k] = $v;
    }

    $_SERVER = $this->serverVars();
new ResponseFactory();
    return ServerRequestFactory::createFromGlobals()->withParsedBody($data);
  }

  protected function serverVars() {
    return array (
        'REDIRECT_STATUS' => '200',
        'CONTENT_TYPE' => 'application/json',
        'HTTP_USER_AGENT' => 'PostmanRuntime/7.22.0',
        'HTTP_ACCEPT' => '*/*',
        'HTTP_CACHE_CONTROL' => 'no-cache',
        'HTTP_POSTMAN_TOKEN' => '3976e9f4-1896-4c02-8d20-b9b7e98b2ccc',
        'HTTP_HOST' => 'localhost:8080',
        'HTTP_ACCEPT_ENCODING' => 'gzip, deflate, br',
        'CONTENT_LENGTH' => '83',
        'HTTP_CONNECTION' => 'keep-alive',
        'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin',
        'SERVER_SIGNATURE' => '<address>Apache/2.4.25 (Debian) Server at localhost Port 8080</address>
',
        'SERVER_SOFTWARE' => 'Apache/2.4.25 (Debian)',
        'SERVER_NAME' => 'localhost',
        'SERVER_ADDR' => '172.21.0.3',
        'SERVER_PORT' => '8080',
        'REMOTE_ADDR' => '172.21.0.1',
        'DOCUMENT_ROOT' => '/var/www/html/public',
        'REQUEST_SCHEME' => 'http',
        'CONTEXT_PREFIX' => '',
        'CONTEXT_DOCUMENT_ROOT' => '/var/www/html/public',
        'SERVER_ADMIN' => 'webmaster@localhost',
        'SCRIPT_FILENAME' => '/var/www/html/public/index.php',
        'REMOTE_PORT' => '48076',
        'REDIRECT_URL' => '/docdata',
        'REDIRECT_QUERY_STRING' => '_url=/docdata',
        'GATEWAY_INTERFACE' => 'CGI/1.1',
        'SERVER_PROTOCOL' => 'HTTP/1.1',
        'REQUEST_METHOD' => 'POST',
        'QUERY_STRING' => '_url=/docdata',
        'REQUEST_URI' => '/docdata',
        'SCRIPT_NAME' => '/index.php',
        'PHP_SELF' => '/index.php',
        'REQUEST_TIME_FLOAT' => 1581117051.279,
        'REQUEST_TIME' => 1581117051,
        'argv' =>
            array (
                0 => '_url=/docdata',
            ),
        'argc' => 1,
    );
  }
}