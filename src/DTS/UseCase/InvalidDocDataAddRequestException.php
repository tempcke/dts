<?php declare(strict_types=1);


namespace HomeCEU\DTS\UseCase;


use Throwable;

class InvalidDocDataAddRequestException extends \RuntimeException {
  public $errors = [];
  public function __construct($message = "", $code = 0, Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  public function setErrors(array $errors) {
    $this->errors = $errors;
    return $this;
  }
}
