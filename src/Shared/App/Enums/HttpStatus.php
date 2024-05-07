<?php

namespace Shared\App\Enums;

/**
 * Class HttpStatus
 *
 * This class provides constants for HTTP status codes and their corresponding messages.
 *
 * @package Shared\App\Enums
 */
enum HttpStatus: int
{
    // Informational responses
    case CONTINUE = 100;
    case SWITCHING_PROTOCOLS = 101;
    case PROCESSING = 102;
    case EARLY_HINTS = 103;

    // Successful responses
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NON_AUTHORITATIVE_INFORMATION = 203;
    case NO_CONTENT = 204;
    case RESET_CONTENT = 205;
    case PARTIAL_CONTENT = 206;
    case MULTI_STATUS = 207;
    case ALREADY_REPORTED = 208;
    case IM_USED = 226;

    // Redirection messages
    case MULTIPLE_CHOICES = 300;
    case MOVED_PERMANENTLY = 301;
    case FOUND = 302;
    case SEE_OTHER = 303;
    case NOT_MODIFIED = 304;
    case USE_PROXY = 305;
    case TEMPORARY_REDIRECT = 307;
    case PERMANENT_REDIRECT = 308;

    // Client error responses
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case PAYMENT_REQUIRED = 402;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case PROXY_AUTHENTICATION_REQUIRED = 407;
    case REQUEST_TIMEOUT = 408;
    case CONFLICT = 409;
    case GONE = 410;
    case LENGTH_REQUIRED = 411;
    case PRECONDITION_FAILED = 412;
    case PAYLOAD_TOO_LARGE = 413;
    case URI_TOO_LONG = 414;
    case UNSUPPORTED_MEDIA_TYPE = 415;
    case RANGE_NOT_SATISFIABLE = 416;
    case EXPECTATION_FAILED = 417;
    case IM_A_TEAPOT = 418;
    case MISDIRECTED_REQUEST = 421;
    case UNPROCESSABLE_ENTITY = 422;
    case LOCKED = 423;
    case FAILED_DEPENDENCY = 424;
    case TOO_EARLY = 425;
    case UPGRADE_REQUIRED = 426;
    case PRECONDITION_REQUIRED = 428;
    case TOO_MANY_REQUESTS = 429;
    case REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    case UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    // Server error responses
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;
    case HTTP_VERSION_NOT_SUPPORTED = 505;
    case VARIANT_ALSO_NEGOTIATES = 506;
    case INSUFFICIENT_STORAGE = 507;
    case LOOP_DETECTED = 508;
    case NOT_EXTENDED = 510;
    case NETWORK_AUTHENTICATION_REQUIRED = 511;


    /**
     * Get the name of the HTTP status code.
     *
     * This function takes an HTTP status code as an integer and returns the corresponding
     * name as a string. If the status code is not defined in the HttpStatus enumeration,
     * it returns 'UNKNOWN_STATUS'.
     *
     * @param int $code The HTTP status code.
     * @return string The name of the HTTP status code.
     */
    public static function getStatus(int $code): string
    {
        /**
         * Array mapping HTTP status codes to their corresponding messages.
         * Each key is an HTTP status code, and each value is the corresponding name.
         */
        $messages = [
            HttpStatus::CONTINUE->value => HttpStatus::CONTINUE->name,
            HttpStatus::SWITCHING_PROTOCOLS->value => HttpStatus::SWITCHING_PROTOCOLS->name,
            HttpStatus::PROCESSING->value => HttpStatus::PROCESSING->name,
            HttpStatus::EARLY_HINTS->value => HttpStatus::EARLY_HINTS->name,
            HttpStatus::OK->value => HttpStatus::OK->name,
            HttpStatus::CREATED->value => HttpStatus::CREATED->name,
            HttpStatus::ACCEPTED->value => HttpStatus::ACCEPTED->name,
            HttpStatus::NON_AUTHORITATIVE_INFORMATION->value => HttpStatus::NON_AUTHORITATIVE_INFORMATION->name,
            HttpStatus::NO_CONTENT->value => HttpStatus::NO_CONTENT->name,
            HttpStatus::RESET_CONTENT->value => HttpStatus::RESET_CONTENT->name,
            HttpStatus::PARTIAL_CONTENT->value => HttpStatus::PARTIAL_CONTENT->name,
            HttpStatus::MULTI_STATUS->value => HttpStatus::MULTI_STATUS->name,
            HttpStatus::ALREADY_REPORTED->value => HttpStatus::ALREADY_REPORTED->name,
            HttpStatus::IM_USED->value => HttpStatus::IM_USED->name,
            HttpStatus::MULTIPLE_CHOICES->value => HttpStatus::MULTIPLE_CHOICES->name,
            HttpStatus::MOVED_PERMANENTLY->value => HttpStatus::MOVED_PERMANENTLY->name,
            HttpStatus::FOUND->value => HttpStatus::FOUND->name,
            HttpStatus::SEE_OTHER->value => HttpStatus::SEE_OTHER->name,
            HttpStatus::NOT_MODIFIED->value => HttpStatus::NOT_MODIFIED->name,
            HttpStatus::USE_PROXY->value => HttpStatus::USE_PROXY->name,
            HttpStatus::TEMPORARY_REDIRECT->value => HttpStatus::TEMPORARY_REDIRECT->name,
            HttpStatus::PERMANENT_REDIRECT->value => HttpStatus::PERMANENT_REDIRECT->name,
            HttpStatus::BAD_REQUEST->value => HttpStatus::BAD_REQUEST->name,
            HttpStatus::UNAUTHORIZED->value => HttpStatus::UNAUTHORIZED->name,
            HttpStatus::PAYMENT_REQUIRED->value => HttpStatus::PAYMENT_REQUIRED->name,
            HttpStatus::FORBIDDEN->value => HttpStatus::FORBIDDEN->name,
            HttpStatus::NOT_FOUND->value => HttpStatus::NOT_FOUND->name,
            HttpStatus::METHOD_NOT_ALLOWED->value => HttpStatus::METHOD_NOT_ALLOWED->name,
            HttpStatus::NOT_ACCEPTABLE->value => HttpStatus::NOT_ACCEPTABLE->name,
            HttpStatus::PROXY_AUTHENTICATION_REQUIRED->value => HttpStatus::PROXY_AUTHENTICATION_REQUIRED->name,
            HttpStatus::REQUEST_TIMEOUT->value => HttpStatus::REQUEST_TIMEOUT->name,
            HttpStatus::CONFLICT->value => HttpStatus::CONFLICT->name,
            HttpStatus::GONE->value => HttpStatus::GONE->name,
            HttpStatus::LENGTH_REQUIRED->value => HttpStatus::LENGTH_REQUIRED->name,
            HttpStatus::PRECONDITION_FAILED->value => HttpStatus::PRECONDITION_FAILED->name,
            HttpStatus::PAYLOAD_TOO_LARGE->value => HttpStatus::PAYLOAD_TOO_LARGE->name,
            HttpStatus::URI_TOO_LONG->value => HttpStatus::URI_TOO_LONG->name,
            HttpStatus::UNSUPPORTED_MEDIA_TYPE->value => HttpStatus::UNSUPPORTED_MEDIA_TYPE->name,
            HttpStatus::RANGE_NOT_SATISFIABLE->value => HttpStatus::RANGE_NOT_SATISFIABLE->name,
            HttpStatus::EXPECTATION_FAILED->value => HttpStatus::EXPECTATION_FAILED->name,
            HttpStatus::IM_A_TEAPOT->value => HttpStatus::IM_A_TEAPOT->name,
            HttpStatus::MISDIRECTED_REQUEST->value => HttpStatus::MISDIRECTED_REQUEST->name,
            HttpStatus::UNPROCESSABLE_ENTITY->value => HttpStatus::UNPROCESSABLE_ENTITY->name,
            HttpStatus::LOCKED->value => HttpStatus::LOCKED->name,
            HttpStatus::FAILED_DEPENDENCY->value => HttpStatus::FAILED_DEPENDENCY->name,
            HttpStatus::TOO_EARLY->value => HttpStatus::TOO_EARLY->name,
            HttpStatus::UPGRADE_REQUIRED->value => HttpStatus::UPGRADE_REQUIRED->name,
            HttpStatus::PRECONDITION_REQUIRED->value => HttpStatus::PRECONDITION_REQUIRED->name,
            HttpStatus::TOO_MANY_REQUESTS->value => HttpStatus::TOO_MANY_REQUESTS->name,
            HttpStatus::REQUEST_HEADER_FIELDS_TOO_LARGE->value => HttpStatus::REQUEST_HEADER_FIELDS_TOO_LARGE->name,
            HttpStatus::UNAVAILABLE_FOR_LEGAL_REASONS->value => HttpStatus::UNAVAILABLE_FOR_LEGAL_REASONS->name,
            HttpStatus::INTERNAL_SERVER_ERROR->value => HttpStatus::INTERNAL_SERVER_ERROR->name,
            HttpStatus::NOT_IMPLEMENTED->value => HttpStatus::NOT_IMPLEMENTED->name,
            HttpStatus::BAD_GATEWAY->value => HttpStatus::BAD_GATEWAY->name,
            HttpStatus::SERVICE_UNAVAILABLE->value => HttpStatus::SERVICE_UNAVAILABLE->name,
            HttpStatus::GATEWAY_TIMEOUT->value => HttpStatus::GATEWAY_TIMEOUT->name,
            HttpStatus::HTTP_VERSION_NOT_SUPPORTED->value => HttpStatus::HTTP_VERSION_NOT_SUPPORTED->name,
            HttpStatus::VARIANT_ALSO_NEGOTIATES->value => HttpStatus::VARIANT_ALSO_NEGOTIATES->name,
            HttpStatus::INSUFFICIENT_STORAGE->value => HttpStatus::INSUFFICIENT_STORAGE->name,
            HttpStatus::LOOP_DETECTED->value => HttpStatus::LOOP_DETECTED->name,
            HttpStatus::NOT_EXTENDED->value => HttpStatus::NOT_EXTENDED->name,
            HttpStatus::NETWORK_AUTHENTICATION_REQUIRED->value => HttpStatus::NETWORK_AUTHENTICATION_REQUIRED->name,
        ];

        // Return the name of the HTTP status code, or 'UNKNOWN_STATUS' if the code is not defined.
        return $messages[$code] ?? 'UNKNOWN_STATUS';
    }
}