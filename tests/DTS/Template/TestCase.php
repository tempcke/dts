<?php


namespace HomeCEU\Tests\DTS\Template;


use HomeCEU\DTS\Render\Renderer;

class TestCase extends \HomeCEU\Tests\DTS\TestCase {
  protected $renderer;

  protected function setUp(): void
  {
    $this->renderer = Renderer::create();
  }
}
