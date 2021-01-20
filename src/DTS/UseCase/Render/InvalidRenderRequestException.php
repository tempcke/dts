<?php


namespace HomeCEU\DTS\UseCase\Render;

use Throwable;

class InvalidRenderRequestException extends \RuntimeException {

  public function __construct($message = "", $code = 0, Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}
