<?php


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Render\TemplateCompiler;
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
  /** @var Persistence\InMemory */
  private $compiledTemplatePersistence;

  protected function setUp(): void {
    parent::setUp();
    $this->dataPersistence = $this->fakePersistence('docdata', 'dataId');
    $this->dataRepo = new DocDataRepository($this->dataPersistence);

    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $this->compiledTemplatePersistence = $this->fakePersistence('compiled_template', 'templateId');

    $this->templateRepo = new TemplateRepository(
        $this->templatePersistence,
        $this->compiledTemplatePersistence
    );

    $this->render = new Render($this->templateRepo, $this->dataRepo);
  }

  public function testInvalidRequestThrowsException() {
    $this->expectException(InvalidRenderRequestException::class);
    $request = RenderRequest::fromState([]);
    $this->render->renderDoc($request);
  }

  public function testCanResolveRequest() {
    $templateId = 'tid';
    $body = 'Hi {{name}}';
    $this->templatePersistence->persist([
        'docType' => 'dt',
        'templateId' => $templateId,
        'templateKey' => 'tk',
        'body' => $body
    ]);
    $this->compiledTemplatePersistence->persist([
        'templateId' => $templateId,
        'body' => TemplateCompiler::create()->compile($body)
    ]);
    $this->dataPersistence->persist([
        'docType' => 'dt',
        'dataId' => 'did',
        'dataKey' => 'dk',
        'data'=>['name'=>'Fred']
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
    Assert::assertEquals($templateId, $r->completeRequest->templateId);
    Assert::assertEquals('Hi Fred', $docBody);
  }

}
