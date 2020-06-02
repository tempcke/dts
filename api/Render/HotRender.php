<?php declare(strict_types=1);


namespace HomeCEU\DTS\Api\Render;


use HomeCEU\DTS\Persistence\HotRenderPersistence;
use HomeCEU\DTS\Repository\HotRenderRepository;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\UseCase\HotRender as HotRenderUseCase;
use HomeCEU\DTS\UseCase\HotRenderRequest;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Http\Stream;

class HotRender {
  private $useCase;

  public function __construct(ContainerInterface $container) {
    $conn = $container->dbConnection;
    $repo = new HotRenderRepository(new HotRenderPersistence($conn));
    $this->useCase = new HotRenderUseCase($repo);
  }

  public function __invoke(Request $request, Response $response, $args) {
    try {
      $qp = $request->getQueryParams();
      $request = HotRenderRequest::fromState([
          'requestId' => $args['requestId'],
          'format' => empty($qp['format']) ? 'html' : $qp['format']
      ]);
      $renderResponse = $this->useCase->render($request);
      return $response
          ->withHeader('Content-Type', $renderResponse->contentType)
          ->withBody(new Stream(fopen($renderResponse->path, 'r')))
          ->withStatus(200);
    } catch (RecordNotFoundException $e) {
      return $response->withStatus(404);
    }
  }
}
