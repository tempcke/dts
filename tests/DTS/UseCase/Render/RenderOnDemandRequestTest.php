<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase\Render;


use HomeCEU\DTS\UseCase\Render\RenderOnDemandRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class RenderOnDemandRequestTest extends TestCase {
  const DOC_TYPE = 'D';
  const TEMPLATE_KEY = 'TK';
  const A_JSON_STRING = '{"a": "json_string"}';

  public function testBuildFromArray(): void {
    $state = ['docType' => self::DOC_TYPE, 'templateKey' => self::TEMPLATE_KEY, 'docData' => self::A_JSON_STRING];
    $r = RenderOnDemandRequest::fromState($state);
    Assert::assertEquals($state['docType'], $r->docType);
    Assert::assertEquals($state['templateKey'], $r->templateKey);
    Assert::assertEquals($state['docData'], $r->docData);
  }

  public function testValidCases(): void {
    $valid = [
        'docType' => self::DOC_TYPE,
        'templateKey' => self::TEMPLATE_KEY,
        'docData' => self::A_JSON_STRING
    ];
    $r = RenderOnDemandRequest::fromState($valid);
    Assert::assertTrue($r->isValid());
  }

  /** @dataProvider invalidStates() */
  public function testInvalidCases($invalidState): void {
      $r = RenderOnDemandRequest::fromState($invalidState);
      Assert::assertFalse($r->isValid());
  }

  public function invalidStates(): \Generator {
    yield [['docType' => self::DOC_TYPE]];
    yield [['templateKey' => self::TEMPLATE_KEY]];
    yield [['docData' => self::A_JSON_STRING]];
    yield [['docType' => self::DOC_TYPE, 'templateKey' => self::TEMPLATE_KEY]];
    yield [['docType' => self::DOC_TYPE, 'docData' => self::A_JSON_STRING]];
    yield [['templateKey' => self::TEMPLATE_KEY, 'docData' => self::A_JSON_STRING]];
  }
}
