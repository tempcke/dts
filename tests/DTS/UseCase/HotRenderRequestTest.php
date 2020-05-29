<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\UseCase\HotRenderRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class HotRenderRequestTest extends TestCase {
  const FORMAT_HTML = 'html';
  const DOC_TYPE = 'test';
  const TEMPLATE = 'Hi {{ name }}!';
  const DATA = '{"name": "Natalie"}';

  public function testBuildFromArray(): void {
    $state = [
        'format' => self::FORMAT_HTML,
        'docType' => self::DOC_TYPE,
        'template' => self::TEMPLATE,
        'data' => self::DATA
    ];
    $r = HotRenderRequest::fromState($state);
    Assert::assertEquals($state['format'], $r->format);
    Assert::assertEquals($state['docType'], $r->docType);
    Assert::assertEquals($state['template'], $r->template);
    Assert::assertEquals($state['data'], $r->data);
  }

  /** @dataProvider validStates() */
  public function testValidStates($validState): void {
    $r = HotRenderRequest::fromState($validState);
    Assert::assertTrue($r->isValid());
  }

  public function validStates(): \Generator {
    yield [['format' => self::FORMAT_HTML, 'docType' => self::DOC_TYPE, 'template' => self::TEMPLATE, 'data' => self::DATA]];
    yield [['format' => self::FORMAT_HTML, 'template' => self::TEMPLATE, 'data' => self::DATA]];
    yield [['docType' => self::DOC_TYPE, 'template' => self::TEMPLATE, 'data' => self::DATA]];
    yield [['template' => self::TEMPLATE, 'data' => self::DATA]];
  }

  /** @dataProvider invalidStates() */
  public function testInvalidStates($invalidState): void {
    $r = HotRenderRequest::fromState($invalidState);
    Assert::assertFalse($r->isValid());
  }

  public function invalidStates(): \Generator {
    yield [['format' => self::FORMAT_HTML, 'docType' => self::DOC_TYPE, 'data' => self::DATA]];
    yield [['docType' => self::DOC_TYPE, 'data' => self::DATA]];
    yield [['format' => self::FORMAT_HTML, 'data' => self::DATA]];
    yield [['data' => self::DATA]];
    yield [['format' => self::FORMAT_HTML, 'docType' => self::DOC_TYPE, 'template' => self::TEMPLATE]];
    yield [['docType' => self::DOC_TYPE, 'template' => self::TEMPLATE]];
    yield [['format' => self::FORMAT_HTML, 'template' => self::TEMPLATE]];
    yield [['template' => self::TEMPLATE]];
  }
}
