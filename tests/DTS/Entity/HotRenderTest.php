<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\Entity;


use HomeCEU\DTS\Entity\HotRender;
use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class HotRenderTest extends TestCase {
  public function testBuildFromState(): void {
    $state = [
        'requestId' => 'RID',
        'template' => 'Hi {{ name }}',
        'data' => ['name' => 'test'],
        'createdAt' => new \DateTime('yesterday')
    ];
    $r = HotRender::fromState($state);
    Assert::assertEquals($state['requestId'], $r->requestId);
    Assert::assertEquals($state['template'], $r->template);
    Assert::assertEquals($state['data'], $r->data);
    Assert::assertEquals($state['createdAt'], $r->createdAt);
  }
}
