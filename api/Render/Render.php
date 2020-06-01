<?php


namespace HomeCEU\DTS\Api\Render;

use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\Render as RenderUseCase;
use HomeCEU\DTS\UseCase\RenderRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Http\Stream;

class Render {
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

  public function __construct(ContainerInterface $container) {
    $conn = $container->dbConnection;

    $this->templateRepo = new TemplateRepository(
        new TemplatePersistence($conn),
        new CompiledTemplatePersistence($conn)
    );
    $this->dataRepo = new DocDataRepository(new DocDataPersistence($conn));
    $this->useCase = new RenderUseCase($this->templateRepo, $this->dataRepo);
  }

  public function __invoke(Request $request, Response $response, $args) {
    try {
      $qp = $request->getQueryParams();
      $renderRequest = RenderRequest::fromState([
          'docType' => $args['docType'],
          'templateKey' => $args['templateKey'],
          'dataKey' => $args['dataKey'],
          'format' => empty($qp['format']) ? 'html' : $qp['format']
      ]);
      $renderResponse = $this->useCase->renderDoc($renderRequest);
      return $response
          ->withHeader('Content-Type', $renderResponse->contentType)
          ->withBody(new Stream(fopen($renderResponse->path, 'r')))
          ->withStatus(200);
    } catch (RecordNotFoundException $e) {
      return $response->withStatus(404);
    }
  }
}
