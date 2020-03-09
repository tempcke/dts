<?php


namespace HomeCEU\DTS\Render;


class TemplateHelpers {
  public static function equal(): Helper {
    return new Helper('eq', function ($val1, $val2) {
      return $val1 == $val2;
    });
  }
}
