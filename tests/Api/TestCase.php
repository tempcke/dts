<?php


namespace HomeCEU\Tests\Api;


use HomeCEU\DTS\Api\App;
use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
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

  /** @var DocDataPersistence */
  protected $docDataPersistence;

  /** @var string */
  protected $docType;

  public function setUp(): void {
    parent::setUp();
    $this->di = new DiContainer();
    $this->app = new App($this->di);

    $this->docType = (new \ReflectionClass($this))->getShortName().'-'.time();
  }

  public function tearDown(): void {
    $db = $this->di->dbConnection;
    $db->deleteWhere(
        DocDataPersistence::TABLE,
        ['doc_type'=>$this->docType]
    );
    $db->deleteWhere(
        TemplatePersistence::TABLE,
        ['doc_type'=>$this->docType]
    );
    $db->deleteWhere(
        TemplatePersistence::TABLE,
        ['doc_type'=>$this->docType . '/partial']
    );
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
    $this->templatePersistence()->persist([
        'docType' => $this->docType,
        'templateKey' => $templateKey,
        'createdAt' => $this->createdAtDateTime(),
        'templateId' => uniqid(),
        'body'=>'Hi {{name}}',
        'author'=>'author',
        'name'=>'name'
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
}