<?php


namespace HomeCEU\DTS\Render;


class TemplateHelpers {
  public static function ifComparisonHelper(): Helper {
    return new Helper('comp-if', function ($arg1, $arg2, $return) {
      return $arg1 == $arg2 ? $return : '';
    });
  }
}
