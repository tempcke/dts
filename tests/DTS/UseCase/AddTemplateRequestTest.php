<?php declare(strict_types=1);


namespace HomeCEU\Tests\DTS\UseCase;


use HomeCEU\Tests\DTS\TestCase;
use PHPUnit\Framework\Assert;

class AddTemplateRequestTest extends TestCase {
  protected $req = ['type', 'key', 'author', 'body'];

  public function testBuildFromArray(): void {
    $state = [
        'type' => 'enrollment',
        'key' => __FUNCTION__,
        'author' => 'test',
        'body' => '1234'
    ];
    $obj = AddTemplateRequest::fromState($state);

    Assert::assertEquals($state['type'], $obj->type);
    Assert::assertEquals($state['key'], $obj->key);
    Assert::assertEquals($state['author'], $obj->author);
    Assert::assertEquals($state['body'], $obj->body);
  }
}
