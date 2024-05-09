<?php

namespace Shared\App\Router\Enums;

/**
 * Class HttpVerbs
 *
 * This class provides constants for HTTP verbs (methods).
 * Using these constants instead of string literals in your code can reduce the risk of typos and make your code more consistent and easier to read and maintain.
 */
enum HttpVerbs: string
{
    /**
     * Constant for the HTTP GET method.
     */
    case GET = 'GET';

    /**
     * Constant for the HTTP POST method.
     */
    case POST = 'POST';

    /**
     * Constant for the HTTP PUT method.
     */
    case PUT = 'PUT';

    /**
     * Constant for the HTTP PATCH method.
     */
    case PATCH = 'PATCH';

    /**
     * Constant for the HTTP DELETE method.
     */
    case DELETE = 'DELETE';

    /**
     * Constant for the HTTP HEAD method.
     */
    case HEAD = 'HEAD';

    /**
     * Constant for the HTTP OPTIONS method.
     */
    case OPTIONS = 'OPTIONS';

    /**
     * Constant for the HTTP CONNECT method.
     */
    case CONNECT = 'CONNECT';

    /**
     * Constant for the HTTP TRACE method.
     */
    case TRACE = 'TRACE';
}
