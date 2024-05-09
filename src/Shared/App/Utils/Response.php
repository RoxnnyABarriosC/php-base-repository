<?php

use JetBrains\PhpStorm\NoReturn;
use Ramsey\Uuid\Uuid;
use Respect\Validation\Exceptions\NestedValidationException;
use Shared\App\Enums\HttpStatus;
use Shared\App\Exceptions\HttpException;

DEFINE('CORRELATION_ID', Uuid::uuid4()->toString());

/**
 * Sends a JSON response with the given data and status code.
 *
 * @param mixed $data The data to be sent in the response.
 * @param HttpStatus $statusCode The HTTP status code for the response. Default is 200.
 * @param array $metadata Additional metadata to be included in the response. Default is null.
 * @param array $pagination Pagination information to be included in the response. Default is null.
 * @Param bool $log Whether to log the response. Default is false.
 */
#[NoReturn]
function Response(mixed $data, HttpStatus $statusCode = HttpStatus::OK, array $pagination = [],  array $metadata = [], bool $log = false): never
{
    // Set the HTTP response code
    http_response_code($statusCode->value);

    // Set default values for the options if not provided
    $options = ['metadata' => $metadata, 'pagination' => $pagination];

    // Prepare the response data
    $response = [
        'folio' => CORRELATION_ID,
        'timestamp' => time(),
        'status' => $statusCode->name,
        'data' => $data
    ];

    // Add 'pagination' and 'metadata' to the response if they are not empty
    foreach (['pagination', 'metadata'] as $key) {
        if (!empty($options[$key])) {
            $response[$key] = $options[$key];
        }
    }

    // Encode the response data as JSON
    $response_json = json_encode($response, JSON_UNESCAPED_SLASHES);

    // TODO: Implement logging of the response

    // Send the response
    echo $response_json;

    // Terminate the script
    exit;
}

/**
 * Maps the errors from a NestedValidationException to an array.
 *
 * @param NestedValidationException $exception The exception to map the errors from.
 * @return array The mapped errors.
 */
function MapErrors(NestedValidationException $exception): array
{
    return array_map(function ($field, $message) {
        return ['field' => $field, 'message' => $message];
    }, array_keys($exception->getMessages()), $exception->getMessages());
}

/**
 * Handles exceptions by sending a JSON response with the exception details.
 */
#[NoReturn]
function ExceptionHandler($exception): never
{
    $statusCode = $exception->getCode() ?: HttpStatus::INTERNAL_SERVER_ERROR->value;

    $response = [
        'folio' => CORRELATION_ID,
        'timestamp' => time(),
        'status' => HttpStatus::getStatus($statusCode),
        'errorMessage' => $exception->getMessage(),
        'errorCode' => $exception instanceof HttpException ? $exception->getErrorCode() : 'ERROR',
    ];

    if ($exception instanceof NestedValidationException) {
        $statusCode = HttpStatus::BAD_REQUEST->value;
        $response['status'] = HttpStatus::getStatus($statusCode);
        $response['errorMessage'] = 'Validation error';
        $response['errorCode'] = 'VALIDATION_ERROR';
        $response['data'] = MapErrors($exception);
    }

    // Set the HTTP response code
    http_response_code($statusCode);

    // Send the response
    echo json_encode($response, JSON_UNESCAPED_SLASHES);

    // Terminate the script
    exit;
}

// Set the exception handler to the ExceptionHandler function
set_exception_handler('ExceptionHandler');

// Set the correlation ID header
header('X-Correlation-Id: ' . CORRELATION_ID);