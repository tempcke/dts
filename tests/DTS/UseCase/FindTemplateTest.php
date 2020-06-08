<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Entity\Template;
use HomeCEU\DTS\Repository\RecordNotFoundException;
use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\GetTemplate;
use HomeCEU\DTS\UseCase\GetTemplateByIdRequest;
use HomeCEU\DTS\UseCase\FindTemplateRequest;
use HomeCEU\DTS\UseCase\InvalidGetTemplateRequestException;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class GetTemplateTest extends TestCase {
  const DOC_TYPE_ENROLLMENT = 'enrollment';
  const DOC_TYPE_EXAMPLE = 'example';

  private $useCase;
  private $templatePersistence;
  private $templateRepository;

  protected function setUp(): void {
    parent::setUp();
    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $compiledTemplatePersistence = $this->fakePersistence('compiled_template', 'templateId');

    $this->templateRepository = new TemplateRepository($this->templatePersistence, $compiledTemplatePersistence);
    $this->useCase = new GetTemplate($this->templateRepository);
  }

  public function testInvalidRequestThrowsException(): void {
    $this->expectException(InvalidGetTemplateRequestException::class);
    $r = FindTemplateRequest::fromState([]);
    $this->useCase->getTemplates($r);
  }

  public function testGetTemplateById(): void {
    $t1 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $this->persistTemplates($t1);

    Assert::assertEquals($t1, $this->useCase->getTemplateById(
        GetTemplateByIdRequest::fromState(['templateId' => $t1->templateId])
    ));
  }

  public function testGetTemplateByIdNotFound(): void {
    $this->expectException(RecordNotFoundException::class);
    $this->useCase->getTemplateById(GetTemplateByIdRequest::fromState(['templateId' => uniqid()]));
  }

  public function testGetTemplateByType(): void {
    $t1 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $t2 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $t3 = $this->fakeTemplate(self::DOC_TYPE_EXAMPLE);
    $this->persistTemplates($t1, $t2, $t3);

    $r = FindTemplateRequest::fromState(['type' => self::DOC_TYPE_ENROLLMENT]);
    $result = $this->useCase->getTemplates($r);

    Assert::assertContainsEquals($t1, $result);
    Assert::assertContainsEquals($t2, $result);
    Assert::assertNotContainsEquals($t3, $result);
  }

  public function testGetTemplateByTypeAndKey(): void {
    $t1 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $t2 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT);
    $this->persistTemplates($t1, $t2);

    $result = $this->useCase->getTemplates(
        $this->createRequest(self::DOC_TYPE_ENROLLMENT, $t1->templateKey)
    );

    Assert::assertContainsEquals($t1, $result);
    Assert::assertNotContainsEquals($t2, $result);
  }

  public function testGetOnlyNewestForEachTypeAndKeyCombination(): void {
    $sharedKey = uniqid();

    $t1 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT, $sharedKey);
    $t2 = $this->fakeTemplate(self::DOC_TYPE_ENROLLMENT, $sharedKey);
    $t1->createdAt = new \DateTime('-1 day');
    $t2->createdAt = new \DateTime('-1 week');

    $this->persistTemplates($t1, $t2);
    $result = $this->useCase->getTemplates(
        $this->createRequest(self::DOC_TYPE_ENROLLMENT, $sharedKey)
    );
    Assert::assertContainsEquals($t1, $result);
    Assert::assertNotContainsEquals($t2, $result);
  }

  private function createRequest($type, $key = null, $search = null): FindTemplateRequest {
    return FindTemplateRequest::fromState([
        'type' => $type,
        'key' => $key,
        'search' => $search,
    ]);
  }

  private function persistTemplates(Template ...$templates): void {
    foreach ($templates as $template) {
      $this->templatePersistence->persist($template->toArray());
    }
  }
}
