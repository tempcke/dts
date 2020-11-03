<?php


namespace HomeCEU\DTS\Api\DocData;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\UseCase\DocDataVersionList;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\NotFoundException;

class Exists {

  /** @var  Persistence */
  private $persistence;

  /** @var  DocDataRepository */
  private $repository;

  /** @var DocDataVersionList */
  private $useCase;

  /** @var ContainerInterface */
  private $di;

  public function __construct(ContainerInterface $diContainer) {
    $this->di = $diContainer;
    $this->persistence = new DocDataPersistence($this->di->dbConnection);
    $this->repository = new DocDataRepository($this->persistence);
    $this->useCase = new DocDataVersionList($this->repository);
  }

  public function __invoke(Request $request, Response $response, $args) {
    $versions = $this->useCase->versions($args['docType'], $args['dataKey']);
    if (count($versions) < 1) {
      throw new NotFoundException($request, $response);
    }
    return $response->withStatus(200);
  }
}