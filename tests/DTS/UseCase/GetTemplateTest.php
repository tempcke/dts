<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\GetTemplate;
use HomeCEU\DTS\UseCase\GetTemplateRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class GetTemplateTest extends TestCase {
  const DOC_TYPE_ENROLLMENT = 'enrollment';
  const DOC_TYPE_EXAMPLE = 'example';

  private $useCase;
  private $templateRepository;
  private $templatePersistence;

  protected function setUp(): void {
    parent::setUp();
    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $compiledTemplatePersistence = $this->fakePersistence('compiled_template', 'templateId');

    $this->templateRepository = new TemplateRepository($this->templatePersistence, $compiledTemplatePersistence);
    $this->useCase = new GetTemplate($this->templateRepository);
  }

  public function testGetTemplateById(): void {
    $t1 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $this->persistTemplates($t1);
    Assert::assertEquals($t1, $this->useCase->getTemplateById(
        GetTemplateRequest::fromState(['templateId' => $t1->templateId])
    ));
  }
  public function testGetTemplateByIdNotFound(): void {
    $this->expectException(RecordNotFoundException::class);
    $this->useCase->getTemplateById(GetTemplateRequest::fromState(['templateId' => uniqid()]));
  }

  private function persistTemplates(Template ...$templates): void {
    foreach ($templates as $template) {
      $this->templatePersistence->persist($template->toArray());
    }
  }
}
