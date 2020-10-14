<?php


namespace HomeCEU\DTS\Api\Template;

use DateTime;
use Exception;
use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\UseCase\ListTemplates as ListTemplatesUseCase;
use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\TemplateRepository;
use Slim\Http\Response;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class ListTemplates {

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
      $queryParams = $request->getQueryParams();
      $filter = empty($queryParams['filter']) ? [] : $queryParams['filter'];
      $templates = $this->getTemplates($filter);

      $responseData = [
          'total' => count($templates),
          'items' => array_map([$this, 'templateResponseModel'],$templates)
      ];
      return $response
          ->withStatus(200)
          ->withJson($responseData);
    } catch (Exception $e) {
      return $response->withStatus(500, "failed to list templates");
    }
  }

  protected function templateResponseModel(Template $t): array {
    return [
        'templateId' => $t->templateId,
        'docType' => $t->docType,
        'templateKey' => $t->templateKey,
        'author' => $t->author,
        'createdAt' => $t->createdAt->format(DateTime::W3C),
        'bodyUri' => "/template/{$t->templateId}"
    ];
  }

  protected function getTemplates($filter=[]) {
    if (!empty($filter['search'])) {
      return $this->useCase->search($filter['search']);
    }
    if (!empty($filter['type'])) {
      return $this->useCase->filterByType($filter['type']);
    }
    return $this->useCase->all();
  }
}