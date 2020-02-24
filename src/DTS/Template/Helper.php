<?php


namespace HomeCEU\DTS\Template;


class Helper {
  public $name;
  public $func;

  public function __construct(string $name, callable $func) {
    $this->name = $name;
    $this->func = $func;
  }
}