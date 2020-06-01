<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\DTS\UseCase\AddHotRenderRequest;
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
}
