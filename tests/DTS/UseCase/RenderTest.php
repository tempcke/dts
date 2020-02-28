<?php


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\InvalidRenderRequestException;
use HomeCEU\DTS\UseCase\Render;
use HomeCEU\DTS\UseCase\RenderRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class RenderTest extends TestCase {
  /** @var Render */
  private $render;
  /** @var DocDataRepository */
  protected $dataRepo;
  /** @var TemplateRepository */
  protected $templateRepo;
  /** @var Persistence\InMemory */
  protected $dataPersistence;
  /** @var Persistence\InMemory */
  protected $templatePersistence;

  protected function setUp(): void {
    parent::setUp();
    $this->dataPersistence = $this->fakePersistence('docdata', 'dataId');
    $this->dataRepo = new DocDataRepository($this->dataPersistence);

    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $this->templateRepo = new TemplateRepository(
        $this->templatePersistence,
        $this->fakePersistence('compiled_template', 'templateId')
    );

    $this->render = new Render($this->templateRepo, $this->dataRepo);
  }

  public function testInvalidRequestThrowsException() {
    $this->expectException(InvalidRenderRequestException::class);
    $request = RenderRequest::fromState([]);
    $this->render->renderDoc($request);
  }

  public function testCanResolveRequest() {
    $this->dataPersistence->persist([
        'docType' => 'dt',
        'dataId' => 'did',
        'dataKey' => 'dk',
        'data'=>['name'=>'Fred']
    ]);
    $this->templatePersistence->persist([
        'docType' => 'dt',
        'templateId' => 'tid',
        'templateKey' => 'tk',
        'body'=>'Hi {{name}}'
    ]);
    $request = RenderRequest::fromState([
        'docType' => 'dt',
        'templateKey' => 'tk',
        'dataKey' => 'dk'
    ]);

    $r = $this->render;

    $result = $this->render->renderDoc($request);
    $docBody = stream_get_contents($result);
    Assert::assertEquals('did', $r->completeRequest->dataId);
    Assert::assertEquals('tid', $r->completeRequest->templateId);
    Assert::assertEquals('Hi Fred', $docBody);
  }

}
