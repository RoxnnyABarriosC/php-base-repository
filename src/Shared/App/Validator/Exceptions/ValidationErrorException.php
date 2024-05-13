<?php

namespace Shared\App\Validator\Exceptions;

use Shared\App\Router\Enums\HttpStatus;
use Shared\App\Router\Exceptions\HttpException;
use Shared\App\Validator\ConstraintErrorModel;

/**
 * Class ValidationErrorException
 *
 * This class represents a specific type of HttpException that is thrown when a validation error occurs.
 * It contains an array of errors that caused the exception to be thrown.
 */
class ValidationErrorException extends HttpException
{
    /**
     * Constructor for the ValidationErrorException class.
     *
     * @param ConstraintErrorModel[] $errors The array of errors that caused the exception.
     * @param HttpStatus $status The HTTP status code for the exception.
     */
    public function __construct(public array $errors, HttpStatus $status = HttpStatus::BAD_REQUEST)
    {
        parent::__construct($status, 'Validation error', 'VALIDATION_ERROR');
    }

    /**
     * Get the array of errors that caused the exception.
     *
     * @return ConstraintErrorModel[] The array of errors.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}