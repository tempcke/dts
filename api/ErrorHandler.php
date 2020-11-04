<?php


namespace HomeCEU\DTS\Api;

use Exception;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class ErrorHandler
 * @package HomeCEU\DTS\Api
 * https://www.slimframework.com/docs/v3/handlers/error.html
 *
 * This is triggered whenever an exception is thrown past the route handlers
 */
class ErrorHandler {
  /**
   * @var DiContainer
   */
  private $di;

  public function __construct(DiContainer $di) {
    $this->di = $di;
  }

  public function __invoke(Request $r, Response $response, \Throwable $exception) {
    $requestId = uniqid('REQ_');
    $this->di->logger->error($exception, [
        'Request' => [
            $requestId,
            $r->getMethod().' '.$r->getUri()->getPath(),
            $r->getQueryParams()
        ],
    ]);
    return $response
        ->withStatus(500)
        ->withHeader('Content-Type', 'text/html')
        ->write("Something unexpected went wrong!  Please report this issue referencing {$requestId}");
  }
}