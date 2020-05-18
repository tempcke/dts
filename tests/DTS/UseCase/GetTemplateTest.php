<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\GetTemplate;
use HomeCEU\DTS\UseCase\GetTemplateRequest;
use HomeCEU\DTS\UseCase\InvalidGetTemplateRequestException;
use HomeCEU\Tests\DTS\TestCase;

class GetTemplateTest extends TestCase {
  private $usecase;
  private $templatePersistence;
  private $templateRepository;

  protected function setUp(): void {
    parent::setUp();
    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $compiledTemplatePersistence = $this->fakePersistence('compiled_template', 'templateId');

    $this->templateRepository = new TemplateRepository($this->templatePersistence, $compiledTemplatePersistence);
    $this->usecase = new GetTemplate($this->templateRepository);
  }

  public function testInvalidRequestThrowsException(): void {
    $this->expectException(InvalidGetTemplateRequestException::class);
    $r = GetTemplateRequest::fromState([]);
    $this->usecase->getTemplate($r);
  }

  public function testGetTemplateByType(): void {
    $template = $this->fakeTemplateArray();
    $r = GetTemplateRequest::fromState(['type' => $template['docType']]);
    $this->templatePersistence->persist($template);
    dump($this->templatePersistence);

    dump($this->templateRepository->findByDocType($template['docType']));
  }
}
