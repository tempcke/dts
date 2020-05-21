<?php


namespace HomeCEU\Tests\Api;


use HomeCEU\DTS\Api\App;
use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Render\TemplateCompiler;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Environment;
use Slim\Http\Request;

class TestCase extends \HomeCEU\Tests\TestCase {

  /** @var DiContainer */
  protected $di;

  /** @var App */
  protected $app;

  /** @var TemplatePersistence */
  private $templatePersistence;

  /** @var CompiledTemplatePersistence */
  private $compiledTemplatePersistence;

  /** @var DocDataPersistence */
  protected $docDataPersistence;

  /** @var string */
  protected $docType;

  protected function setUp(): void {
    parent::setUp();
    $this->di = new DiContainer();
    $this->di->dbConnection->beginTransaction();
    $this->app = new App($this->di);

    $this->docType = (new \ReflectionClass($this))->getShortName().'-'.time();
  }

  protected function tearDown(): void {
    $db = $this->di->dbConnection;
    $db->rollBack();
    parent::tearDown();
  }

  protected function docDataPersistence(): DocDataPersistence {
    if (empty($this->docDataPersistence)) {
      $this->docDataPersistence = new DocDataPersistence($this->di->dbConnection);
    }
    return $this->docDataPersistence;
  }

  protected function templatePersistence(): TemplatePersistence {
    if (empty($this->templatePersistence)) {
      $this->templatePersistence = new TemplatePersistence($this->di->dbConnection);
    }
    return $this->templatePersistence;
  }

  protected function compiledTemplatePersistence(): CompiledTemplatePersistence {
    if (empty($this->compiledTemplatePersistence)) {
      $this->compiledTemplatePersistence = new CompiledTemplatePersistence($this->di->dbConnection);
    }
    return $this->compiledTemplatePersistence;
  }

  protected function addDocDataFixture($dataKey) {
    $this->docDataPersistence()->persist([
        'docType' => $this->docType,
        'dataKey' => $dataKey,
        'createdAt' => $this->createdAtDateTime(),
        'dataId' => uniqid(),
        'data' => ['name'=>'Fred']
    ]);
  }

  protected function addTemplateFixture($templateKey) {
    $id = uniqid();
    $body = 'Hi {{name}}';
    $this->templatePersistence()->persist([
        'docType' => $this->docType,
        'templateKey' => $templateKey,
        'createdAt' => $this->createdAtDateTime(),
        'templateId' => $id,
        'body'=> $body,
        'author'=>'author',
        'name'=>'name'
    ]);
    $this->compiledTemplatePersistence()->persist([
        'templateId' => $id,
        'body' => TemplateCompiler::create()->compile($body)
    ]);
  }

  protected function createdAtDateTime(): \DateTime {
    static $date;
    if (empty($date)) {
      $date = '2000-01-01';
    }
    $dt = new \DateTime($date.' + 1day');
    $date = $dt->format('Y-m-d');
    return $dt;
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
    if (strstr($uri, '?')) {
      list($uri, $queryString) = explode('?', $uri);
      parse_str($queryString, $queryParams);
    }
    else {
      $queryParams = [];
    }
    $method = 'GET';
    $env = Environment::mock([
        'REQUEST_METHOD' => strtoupper($method),
        'REQUEST_URI'    => $uri,
        'CONTENT_TYPE'   => 'application/json'
    ]);
    $req = Request::createFromEnvironment($env)->withQueryParams($queryParams);
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

  protected function assertContentType($contentType, ResponseInterface $response): void {
    $headers = $response->getHeaders();
    Assert::assertStringContainsString($contentType, $headers['Content-Type'][0]);
  }

  protected function assertStatus(int $code, ResponseInterface $response): void {
    Assert::assertEquals($code, $response->getStatusCode(), sprintf("Status %s does not match %s", $response->getStatusCode(), $code));
  }
}
