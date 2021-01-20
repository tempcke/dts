<?php


namespace HomeCEU\Tests\DTS\UseCase\Render;


use HomeCEU\DTS\Persistence;
use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\DocDataRepository;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\Render\InvalidRenderRequestException;
use HomeCEU\DTS\UseCase\Render\Render;
use HomeCEU\DTS\UseCase\Render\RenderFormat;
use HomeCEU\DTS\UseCase\Render\RenderRequest;
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

    $templateId = 'tid';
    $body = 'Hi {{name}}';
    $this->persistTemplate($templateId, $body);
    $this->persistCompiledTemplate($templateId, $body);
    $this->persistData();
  }

  public function testInvalidRequestThrowsException() {
    $this->expectException(InvalidRenderRequestException::class);
    $request = RenderRequest::fromState([]);
    $this->render->renderDoc($request);
  }

  public function testRenderHtmlRequest(): void {
    $request = $this->createRenderRequest(RenderFormat::FORMAT_HTML);
    $response = $this->render->renderDoc($request);

    Assert::assertFileExists($response->path);
    Assert::assertEquals('text/html', $response->contentType);
    Assert::assertContains(mime_content_type($response->path), ['text/plain', 'text/html']);
  }

  public function testRenderPDFRequest(): void {
    $request = $this->createRenderRequest(RenderFormat::FORMAT_PDF);
    $response = $this->render->renderDoc($request);

    Assert::assertFileExists($response->path);
    Assert::assertEquals('application/pdf', $response->contentType);
    Assert::assertEquals('application/pdf', mime_content_type($response->path));
  }

  protected function persistTemplate(string $templateId, string $body): void {
    $this->templatePersistence->persist([
        'docType' => 'dt',
        'templateId' => $templateId,
        'templateKey' => 'tk',
        'body' => $body
    ]);
  }

  protected function persistCompiledTemplate(string $templateId, string $body): void {
    $this->compiledTemplatePersistence->persist([
        'templateId' => $templateId,
        'body' => TemplateCompiler::create()->compile($body)
    ]);
  }

  protected function persistData(): void {
    $this->dataPersistence->persist([
        'docType' => 'dt',
        'dataId' => 'did',
        'dataKey' => 'dk',
        'data' => ['name' => 'Fred']
    ]);
  }

  protected function createRenderRequest(string $format): RenderRequest {
    return RenderRequest::fromState([
        'docType' => 'dt',
        'templateKey' => 'tk',
        'dataKey' => 'dk',
        'format' => $format
    ]);
  }
}
