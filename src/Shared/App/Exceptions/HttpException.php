<?php

namespace Shared\App\Exceptions;

use Exception;
use Shared\App\Enums\HttpStatus;

class HttpException extends Exception
{

    public function __construct(HttpStatus $statusCode, string $errorMessage, protected string $errorCode)
    {
        parent::__construct($errorMessage, $statusCode->value, null);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}