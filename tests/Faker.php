<?php
namespace HomeCEU\Tests;

use Faker\Generator;
use Faker\Provider as P;

class Faker {

  /** @var Generator */
  private $generator;

  /** @var Faker */
  private static $instance;

  public static function generator() : Generator {
    $self = self::$instance ?: self::$instance = new Faker();
    return $self->generator;
  }

  private function __construct() {
    $g = $this->generator = new Generator();
    foreach ($this->providers() as $p) $g->addProvider($p);
  }

  private function providers() {
    $g = $this->generator;
    return [
        new P\Address($g),
        new P\Base($g),
        new P\Color($g),
        new P\Company($g),
        new P\DateTime($g),
        new P\File($g),
        new P\Internet($g),
        new P\Lorem($g),
        new P\Miscellaneous($g),
        new P\Person($g),
        new P\PhoneNumber($g),
        new P\Uuid($g)
    ];
  }
}