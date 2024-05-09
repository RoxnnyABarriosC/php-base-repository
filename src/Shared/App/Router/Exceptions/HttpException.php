<?php

namespace Shared\App\Router\Exceptions;

use Exception;
use Shared\App\Router\Enums\HttpStatus;

/**
 * Class HttpException
 *
 * This class is a custom exception used to handle HTTP errors in the application.
 * It extends the base Exception class and adds an HTTP status code and an error code.
 * The HTTP status code is an instance of the HttpStatus enum.
 * The error code is a string that provides more specific information about the error.
 *
 * @package Shared\App\Router\Exceptions
 */
class HttpException extends Exception
{
    /**
     * HttpException constructor.
     *
     * Constructs a new instance of the HttpException.
     * The constructor takes an HttpStatus instance, an error message, and an error code as parameters.
     * The HttpStatus instance and the error message are passed to the parent Exception constructor.
     * The error code is stored in a protected property.
     *
     * @param HttpStatus $statusCode The HTTP status code for the exception.
     * @param string $errorMessage The error message for the exception.
     * @param string $errorCode The error code for the exception.
     */
    public function __construct(HttpStatus $statusCode, string $errorMessage, protected string $errorCode)
    {
        parent::__construct($errorMessage, $statusCode->value, null);
    }

    /**
     * Get the error code.
     *
     * This method returns the error code that was passed to the constructor.
     *
     * @return string The error code for the exception.
     */
    public function getErrorCode(): string
    {
        return $this->errorCode;
    }
}