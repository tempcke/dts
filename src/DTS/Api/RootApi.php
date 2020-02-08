<?php


namespace HomeCEU\DTS\Api;


use Slim\Psr7\Request;
use Slim\Psr7\Response;

class RootApi {
  public function get(Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
  }
}