<?php

namespace Shared\App\Validator\Exceptions;

use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Exceptions\HttpException;

class ValidationErrorException extends HttpException
{
    public function __construct(public $errors)
    {
        parent::__construct(HttpStatus::BAD_REQUEST, 'Validation error', 'VALIDATION_ERROR');
    }

    public function getErrors()
    {
        return $this->errors;
    }
}