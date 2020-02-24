<?php


namespace HomeCEU\DTS\Template;


class TemplateHelpers {
  public static function getIfComparisonHelper(): Helper {
    return new Helper('if', function ($arg1, $arg2, $return) {
      return $arg1 == $arg2 ? $return : '';
    });
  }
}