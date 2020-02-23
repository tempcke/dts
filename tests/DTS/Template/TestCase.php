<?php


namespace HomeCEU\Tests\DTS\Template;


use HomeCEU\DTS\Template\Renderer;

class TestCase extends \HomeCEU\Tests\DTS\TestCase {
  protected $renderer;

  protected function setUp(): void
  {
    $this->renderer = new Renderer();
  }
}