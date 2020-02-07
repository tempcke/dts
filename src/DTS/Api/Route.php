<?php
namespace HomeCEU\DTS\Api;

class Route {
  public $method;
  public $uri;
  public $function;

  public function __construct($method, $uri, $function) {
    $this->method = $method;
    $this->uri = $uri;
    $this->function = $function;
  }
}