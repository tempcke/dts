<?php declare(strict_types=1);


namespace HomeCEU\DTS\Api;


class ApiHelper {
  public static function getBaseURL(): string {
    $scheme = $_SERVER['REQUEST_SCHEME'] ?? 'https';
    return "{$scheme}://{$_SERVER['HTTP_HOST']}";
  }

  public static function buildUrl(string $route) {
    return self::getBaseURL() . $route;
  }
}
