<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase\Render;


use HomeCEU\DTS\UseCase\Render\AddHotRenderRequest;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class AddHotRenderRequestTest extends TestCase {
  public function testBuildFromState(): void {
    $state = [
        'template' => '{{ name }}',
        'data' => ['name' => 'Natalie'],
        'docType' => __FUNCTION__
    ];
    $r = AddHotRenderRequest::fromState($state);
    Assert::assertEquals($state['template'], $r->template);
    Assert::assertEquals($state['data'], $r->data);
    Assert::assertEquals($state['docType'], $r->docType);
  }

  /** @dataProvider validStates() */
  public function testValidStates($state): void {
    $r = AddHotRenderRequest::fromState($state);
    Assert::assertTrue($r->isValid());
  }

  public function validStates(): \Generator {
    yield [['template' => 'T', 'data' => ['name' => 'example']]];
    yield [['template' => 'T', 'data' => ['name' => 'example'], 'docType' => __FUNCTION__]];
  }

  /** @dataProvider invalidStates() */
  public function testInvalidStates($state): void {
    $r = AddHotRenderRequest::fromState($state);
    Assert::assertFalse($r->isValid());
  }

  public function invalidStates(): \Generator {
    yield [['data' => ['name' => 'example'], 'docType' => __FUNCTION__]];
    yield [['template' => 'T', 'docType' => __FUNCTION__]];
    yield [['template' => 'T']];
    yield [['data' => ['name' => 'example']]];
  }
}
