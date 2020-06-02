<?php declare(strict_types=1);


namespace DTS\UseCase;


use HomeCEU\DTS\Render\TemplateCompiler;
use HomeCEU\DTS\Repository\HotRenderRepository;
use HomeCEU\DTS\UseCase\GetHotRenderRequest;
use HomeCEU\DTS\UseCase\HotRender;
use HomeCEU\DTS\UseCase\RenderFormat;
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

  public function testRenderHtml(): void {
    $request = $this->fakeHotRenderRequest();
    $this->persistence->persist($request->toArray());

    $rendered = $this->useCase->render(GetHotRenderRequest::fromState([
        'requestId' => $request->requestId,
        'format' => RenderFormat::FORMAT_HTML,
    ]));
    Assert::assertEquals("Hello, World!", file_get_contents($rendered->path));
  }

  public function testRenderPdf(): void {
    $request = $this->fakeHotRenderRequest();
    $this->persistence->persist($request->toArray());

    $response = $this->useCase->render(GetHotRenderRequest::fromState([
        'requestId' => $request->requestId,
        'format' => RenderFormat::FORMAT_PDF,
    ]));
    Assert::assertFileExists($response->path);
    Assert::assertEquals('application/pdf', $response->contentType);
    Assert::assertEquals('application/pdf', mime_content_type($response->path));
  }

  protected function fakeHotRenderRequest() {
    return $this->repo->newHotRenderRequest($this->compiled, ['name' => 'World']);
  }
}
