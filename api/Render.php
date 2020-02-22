<?php


namespace HomeCEU\DTS\Api;

use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\Render as RenderUseCase;
use HomeCEU\DTS\UseCase\RenderRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamInterface;
use Slim\Http\Stream;

class Render {
  /** @var ContainerInterface */
  private $di;
  /**
   * @var DocDataRepository
   */
  private $dataRepo;
  /**
   * @var RenderUseCase
   */
  private $useCase;
  /**
   * @var TemplateRepository
   */
  private $templateRepo;

  public function __construct(ContainerInterface $diContainer) {
    $this->di = $diContainer;
    $this->templateRepo = new TemplateRepository(new TemplatePersistence($this->di->dbConnection));
    $this->dataRepo = new DocDataRepository(new DocDataPersistence($this->di->dbConnection));
    $this->useCase = new RenderUseCase($this->templateRepo, $this->dataRepo);
  }

  public function __invoke(Request $request, Response $response, $args) {
    $query = $request->getQueryParams();
    $renderRequest = RenderRequest::fromState($query);
    $stream = $this->useCase->renderDoc($renderRequest);
    // $docBody = stream_get_contents($stream);
    return $response
        ->withBody(new Stream($stream))
        ->withStatus(200);
  }
}