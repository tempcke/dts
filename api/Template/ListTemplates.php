<?php


namespace HomeCEU\DTS\Api\Template;

use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\UseCase\ListTemplates as ListTemplatesUseCase;
use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\TemplateRepository;
use Psr\Container\ContainerInterface;
use Slim\Http\Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListTemplates {
  /** @var TemplateRepository */
  private $repo;

  /** @var ListTemplatesUseCase */
  private $useCase;

  public function __construct(ContainerInterface $diContainer) {
    $db = $diContainer->dbConnection;
    $repo = new TemplateRepository(
        new TemplatePersistence($db),
        new CompiledTemplatePersistence($db)
    );

    $this->useCase = new ListTemplatesUseCase($repo);
  }

  public function __invoke(Request $request, Response $response, $args) {
    try {
      $templates = [];
      $queryParams = $request->getQueryParams();
      if (array_key_exists('filter', $queryParams)) {
        $filter = $queryParams['filter'];
        if (array_key_exists('search', $filter)) {
          $templates = $this->useCase->search($filter['search']);
        }
      }

      $responseData = [
          'total' => count($templates),
          'items' => array_map(function(Template $t) {
            return $t->toArray();
          },$templates)
      ];
      return $response
          ->withStatus(200)
          ->withJson($responseData);
    } catch (\Exception $e) {
      return $response->withStatus(500, "failed to list templates");
    }
  }
}