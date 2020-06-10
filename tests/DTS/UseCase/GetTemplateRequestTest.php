<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\UseCase\GetTemplateRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class GetTemplateRequestTest extends TestCase {
  public function testBuildFromArray(): void {
    $state = ['templateId' => 'TID', 'docType' => 'DT', 'templateKey' => 'KEY'];
    $r = GetTemplateRequest::fromState($state);

    Assert::assertEquals($state['templateId'], $r->templateId);
    Assert::assertEquals($state['docType'], $r->docType);
    Assert::assertEquals($state['templateKey'], $r->templateKey);
  }

  /** @dataProvider validStates() */
  public function testValidCases(array $state): void {
    $r = GetTemplateRequest::fromState($state);
    Assert::assertTrue($r->isValid());
  }

  public function validStates(): \Generator {
    yield [['templateId' => 'TID']];
    yield [['templateId' => 'TID', 'docType' => 'DT', 'templateKey' => 'KEY']];
    yield [['docType' => 'DT', 'templateKey' => 'KEY']];
  }

  /** @dataProvider invalidStates() */
  public function testInvalidStates(array $state): void {
    $r = GetTemplateRequest::fromState($state);
    Assert::assertFalse($r->isValid());
  }

  public function invalidStates(): \Generator {
    yield [['docType' => 'DT']];
    yield [['templateKey' => 'KEY']];
  }
}
