<?php


namespace HomeCEU\DTS\Api;


use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Psr\Log\LoggerInterface;

class Logger {
  /** @var \Monolog\Logger */
  private static $instance;

  public static function instance(): LoggerInterface {
    if (empty(self::$instance)) {
      $logger = new \Monolog\Logger('applog');
      $logger->pushHandler(self::monologHandler());
      self::$instance = $logger;
    }
    return self::$instance;
  }

  public static function logDir() {
    $appLogDir = getenv('APP_LOG_DIR') ?: APP_ROOT.'/log';
    return substr($appLogDir, 0, 1) == '/'
        ? $appLogDir
        : APP_ROOT.'/'.$appLogDir;
  }

  protected static function monologHandler() {
    $logFile = self::logDir().'/app.log';
    $h = new StreamHandler($logFile, \Monolog\Logger::NOTICE);
    $h->setFormatter(self::monologFormatter());
    return $h;
  }

  protected static function monologFormatter(): FormatterInterface {
    $formatter = new LineFormatter(
        LineFormatter::SIMPLE_FORMAT,
        LineFormatter::SIMPLE_DATE
    );
    $formatter->includeStacktraces(true);
    return $formatter;
  }
}