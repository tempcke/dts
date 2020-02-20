<?php


namespace HomeCEU\DTS\Api\DocData;


use HomeCEU\DTS\Api\DiContainer;
use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\UseCase\AddDocData;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class DocDataAdd {
  /** @var  Persistence */
  private $persistence;

  /** @var  DocDataRepository */
  private $repository;

  /** @var AddDocData */
  private $useCase;

  /** @var ContainerInterface */
  private $di;

  public function __construct(ContainerInterface $diContainer) {
    $this->di = $diContainer;
    $this->persistence = new DocDataPersistence($this->di->dbConnection);
    $this->repository = new DocDataRepository($this->persistence);
    $this->useCase = new AddDocData($this->repository);
  }

  public function __invoke(Request $request, Response $response, $args) {
    $reqData = $request->getParsedBody();
    $docData = $this->useCase->add(
        $reqData['docType'],
        $reqData['dataKey'],
        $reqData['data']
    );
    $savedDocData = $this->persistence->retrieve(
        $docData['dataId'],
        [
            'dataId',
            'docType',
            'dataKey',
            'createdAt'
        ]
    );
    $jsonString = json_encode($savedDocData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($jsonString);
    return $response;
  }

}