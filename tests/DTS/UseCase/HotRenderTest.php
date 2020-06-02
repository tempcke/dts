<?php declare(strict_types=1);


namespace DTS\UseCase;


use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\HotRenderRepository;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\UseCase\GetHotRenderRequest;
use HomeCEU\DTS\UseCase\HotRender;
use HomeCEU\DTS\UseCase\RenderFormat;
use HomeCEU\DTS\UseCase\RenderResponse;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class HotRenderTest extends TestCase {
  private $compiled;
  private $persistence;
  private $useCase;
  private $repo;

  protected function setUp(): void {
    parent::setUp();
    $this->persistence = $this->fakePersistence('hotrender_request', 'requestId');
    $this->repo = new HotRenderRepository($this->persistence);
    $this->compiled = TemplateCompiler::create()->compile('Hello, {{ name }}!');

    $this->useCase = new HotRender($this->repo);
  }

  public function testRequestNotFound(): void {
    $this->expectException(RecordNotFoundException::class);
    $this->render('madeupid', RenderFormat::FORMAT_HTML);
  }

  public function testRenderHtml(): void {
    $request = $this->fakeHotRenderRequest();
    $this->persistence->persist($request->toArray());

    $response = $this->render($request->requestId, RenderFormat::FORMAT_HTML);
    Assert::assertEquals("Hello, World!", file_get_contents($response->path));
  }

  public function testRenderPdf(): void {
    $request = $this->fakeHotRenderRequest();
    $this->persistence->persist($request->toArray());

    $response = $this->render($request->requestId, RenderFormat::FORMAT_PDF);
    Assert::assertFileExists($response->path);
    Assert::assertEquals('application/pdf', $response->contentType);
    Assert::assertEquals('application/pdf', mime_content_type($response->path));
  }

  protected function render($requestId, $format): RenderResponse {
    return $this->useCase->render(GetHotRenderRequest::fromState([
        'requestId' => $requestId,
        'format' => $format,
    ]));
  }

  protected function fakeHotRenderRequest() {
    return $this->repo->newHotRenderRequest($this->compiled, ['name' => 'World']);
  }
}
