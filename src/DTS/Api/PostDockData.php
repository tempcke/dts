<?php


namespace HomeCEU\DTS\Api;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Persistence\InMemory\DocDataPersistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\UseCase\AddDocData;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


class PostDockData {
  /** @var  Persistence */
  private $persistence;

  /** @var  DocDataRepository */
  private $repository;

  /** @var AddDocData */
  private $useCase;

  public function __construct() {
    $this->persistence = new DocDataPersistence();
    $this->repository  = new DocDataRepository($this->persistence);
    $this->useCase     = new AddDocData($this->repository);
  }

  public function __invoke(Request $request, Response $response, $args) {
    $reqData = $request->getParsedBody();
    $docData = $this->useCase->add(
        $reqData['docType'],
        $reqData['dataKey'],
        $reqData['data']
    );
    $savedDocData = $this->persistence->retrieve($docData['dataId']);
    unset($savedDocData['data']);
    $jsonString = json_encode($savedDocData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $response->getBody()->write($jsonString);
    return $response;
  }

}