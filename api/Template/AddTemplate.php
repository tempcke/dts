<?php declare(strict_types=1);


namespace HomeCEU\DTS\Api\Template;


use HomeCEU\DTS\Persistence\CompiledTemplatePersistence;
use HomeCEU\DTS\Persistence\TemplatePersistence;
use HomeCEU\DTS\Repository\TemplateRepository;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AddTemplate {
  private $useCase;

  public function __construct(ContainerInterface $container) {
    $conn = $container->dbConnection;

    $repo = new TemplateRepository(
        new TemplatePersistence($conn),
        new CompiledTemplatePersistence($conn)
    );
    $this->useCase = new \HomeCEU\DTS\UseCase\AddTemplate($repo);
  }

  public function __invoke(Request $request, Response $response): Response {
    return $response->withStatus(400);
  }
}
