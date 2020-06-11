<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\Repository\TemplateRepository;
use HomeCEU\DTS\UseCase\AddTemplate;
use HomeCEU\DTS\UseCase\AddTemplateRequest;
use HomeCEU\DTS\UseCase\InvalidTemplateAddRequestException;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class AddTemplateTest extends TestCase {
  private $templatePersistence;
  private $compiledTemplatePersistence;
  private $useCase;

  protected function setUp(): void {
    parent::setUp();

    $this->templatePersistence = $this->fakePersistence('template', 'templateId');
    $this->compiledTemplatePersistence = $this->fakePersistence('compiled_template', 'templateId');
    $this->useCase = new AddTemplate(new TemplateRepository(
        $this->templatePersistence,
        $this->compiledTemplatePersistence
    ));
  }

  public function testAddTemplateInvalidRequest(): void {
    $this->expectException(InvalidTemplateAddRequestException::class);
    $this->useCase->addTemplate(AddTemplateRequest::fromState([]));
  }

  public function testAddTemplate(): void {
    $request = AddTemplateRequest::fromState([
        'type' => 'test_doctype',
        'key' => uniqid('key_'),
        'author' => 'Author',
        'body' => 'Hi, {{ name }}!'
    ]);
    $template = $this->useCase->addTemplate($request);
    Assert::assertEquals($template->toArray(), $this->templatePersistence->retrieve($template->templateId));
  }

  public function testAddPartial(): void {
    $this->fail('not yet implemented, should not compile partial');
  }

  public function testAddImage(): void {
    $this->fail('not yet implemented, should not compile image');
  }
}
