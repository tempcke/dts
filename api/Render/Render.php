<?php


namespace HomeCEU\DTS\Api\Render;

use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\Render\Render as RenderUseCase;
use HomeCEU\DTS\UseCase\Render\RenderRequest;
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

  public function __construct(DiContainer $di) {
    $db = $di->dbConnection;

    $this->templateRepo = new TemplateRepository(
        new TemplatePersistence($db),
        new CompiledTemplatePersistence($db)
    );
    $this->dataRepo = new DocDataRepository(new DocDataPersistence($db));
    $this->useCase = new RenderUseCase($this->templateRepo, $this->dataRepo);
  }

  public function __invoke(Request $request, Response $response, $args) {
    try {
      $params = array_merge(
          $request->getQueryParams(),
          $args
      );
      $renderRequest = RenderRequest::fromState($params);
      $renderResponse = $this->useCase->renderDoc($renderRequest);
      return $response
          ->withHeader('Content-Type', $renderResponse->contentType)
          ->withBody(new Stream(fopen($renderResponse->path, 'r')))
          ->withStatus(200);
    } catch (RecordNotFoundException $e) {
      return $response->withStatus(404);
    } catch (\Exception $e) {
      return $response->withStatus(500, __CLASS__." failure");
    }
  }

  protected function renderRequestFromArgs($args, $format): RenderRequest {
    return RenderRequest::fromState([
        'docType' => $args['docType'],
        'templateKey' => $args['templateKey'],
        'dataKey' => $args['dataKey'],
        'format' => empty($format) ? 'html' : $format
    ]);
  }
}
