<?php


namespace HomeCEU\DTS\Render;


class Helpers
{
    public static function ifComparisonHelper(): Helper
    {
        return new Helper('if', function ($arg1, $arg2, $return) {
            return $arg1 == $arg2 ? $return : '';
        });
    }
}
