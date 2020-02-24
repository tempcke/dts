<?php


namespace HomeCEU\DTS\Template;


class Partial {
  public $name;
  public $template;

  public function __construct(string $name, string $template) {
    $this->name = $name;
    $this->template = $template;
  }
}