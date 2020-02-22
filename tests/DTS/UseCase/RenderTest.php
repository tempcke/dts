<?php


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\InvalidRenderRequestException;
use HomeCEU\DTS\UseCase\Render;
use HomeCEU\DTS\UseCase\RenderRequest;
use HomeCEU\Tests\DTS\TestCase;

class RenderTest extends TestCase {
  /** @var Render */
  private $render;

  public function setUp(): void {
    parent::setUp();
    $this->render = new Render($this->templateRepository(), $this->docDataRepository());
  }

  public function testInvalidRequestThrowsException() {
    $this->expectException(InvalidRenderRequestException::class);
    $request = RenderRequest::fromState([]);
    $this->render->renderDoc($request);
  }

  protected function docDataRepository() {
    return new DocDataRepository($this->fakePersistence('docdata','dataId'));
  }
  protected function templateRepository() {
    return new TemplateRepository($this->fakePersistence('template','templateId'));
  }

  protected function fakePersistence($table, $idCol) {
    return new class($table, $idCol) extends Persistence\InMemory {
      private $table;
      private $idCol;

      public function __construct($table, $idCol) {
        $this->table = $table;
        $this->idCol = $idCol;
      }

      public function getTable() { return $this->table; }
      public function idColumns(): array { return [$this->idCol]; }
    };
  }
}