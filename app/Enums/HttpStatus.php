<?php

namespace App\Enums;

enum HttpStatus: int
{
    // Success responses
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;

    // Client error responses
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case NOT_ACCEPTABLE = 406;
    case CONFLICT = 409;
    case UNPROCESSABLE_ENTITY = 422;
    case TOO_MANY_REQUESTS = 429;

    // Server error responses
    case INTERNAL_SERVER_ERROR = 500;
    case NOT_IMPLEMENTED = 501;
    case BAD_GATEWAY = 502;
    case SERVICE_UNAVAILABLE = 503;
    case GATEWAY_TIMEOUT = 504;

    public function message(): string
    {
        return match ($this) {
            self::OK => 'Request successful',
            self::CREATED => 'Resource created successfully',
            self::ACCEPTED => 'Request accepted for processing',
            self::NO_CONTENT => 'Request successful, no content to return',
            self::BAD_REQUEST => 'Invalid request data',
            self::UNAUTHORIZED => 'Authentication required',
            self::FORBIDDEN => 'Access denied',
            self::NOT_FOUND => 'Resource not found',
            self::METHOD_NOT_ALLOWED => 'Method not allowed',
            self::NOT_ACCEPTABLE => 'Not acceptable',
            self::CONFLICT => 'Request conflicts with current state',
            self::UNPROCESSABLE_ENTITY => 'Validation failed',
            self::TOO_MANY_REQUESTS => 'Too many requests',
            self::INTERNAL_SERVER_ERROR => 'Internal server error',
            self::NOT_IMPLEMENTED => 'Not implemented',
            self::BAD_GATEWAY => 'Bad gateway',
            self::SERVICE_UNAVAILABLE => 'Service temporarily unavailable',
            self::GATEWAY_TIMEOUT => 'Gateway timeout',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::OK => 'The request was successful.',
            self::CREATED => 'The resource was successfully created.',
            self::ACCEPTED => 'The request has been accepted for processing, but processing is not complete.',
            self::NO_CONTENT => 'The request was successful, but there is no content to return.',
            self::BAD_REQUEST => 'The server could not understand the request due to invalid syntax.',
            self::UNAUTHORIZED => 'The client must authenticate itself to get the requested response.',
            self::FORBIDDEN => 'The client does not have access rights to the content.',
            self::NOT_FOUND => 'The server could not find the requested resource.',
            self::METHOD_NOT_ALLOWED => 'The request method is not supported for the requested resource.',
            self::NOT_ACCEPTABLE => 'The server cannot produce a response matching the Accept headers.',
            self::CONFLICT => 'The request conflicts with the current state of the server.',
            self::UNPROCESSABLE_ENTITY => 'The request was well-formed but contains semantic errors.',
            self::TOO_MANY_REQUESTS => 'The user has sent too many requests in a given time frame.',
            self::INTERNAL_SERVER_ERROR => 'The server encountered a situation it doesn\'t know how to handle.',
            self::NOT_IMPLEMENTED => 'The server does not support the functionality required to fulfill the request.',
            self::BAD_GATEWAY => 'The server received an invalid response from the upstream server.',
            self::SERVICE_UNAVAILABLE => 'The server is not ready to handle the request.',
            self::GATEWAY_TIMEOUT => 'The server did not receive a timely response from the upstream server.',
        };
    }

    public function useCase(): string
    {
        return match ($this) {
            self::OK => 'Use for successful GET requests or operations that return data successfully.',
            self::CREATED => 'Use for successful POST requests that create a new resource (user, article, etc.).',
            self::ACCEPTED => 'Use for asynchronous operations like background tasks, email sending, or queued jobs.',
            self::NO_CONTENT => 'Use for successful DELETE requests or PUT/PATCH requests that don\'t return data.',
            self::BAD_REQUEST => 'Use for validation errors or invalid request parameters.',
            self::UNAUTHORIZED => 'Use when authentication is required but not provided, expired, or invalid tokens.',
            self::FORBIDDEN => 'Use when user is authenticated but lacks permission (e.g., writer trying to access admin features).',
            self::NOT_FOUND => 'Use when a user, article, or any resource doesn\'t exist in the database.',
            self::METHOD_NOT_ALLOWED => 'Use when a method (e.g., POST) is not allowed for a specific endpoint.',
            self::NOT_ACCEPTABLE => 'Use when the client requests a format that the API doesn\'t support.',
            self::CONFLICT => 'Use for business logic conflicts (e.g., duplicate submissions, email already exists).',
            self::UNPROCESSABLE_ENTITY => 'Use for validation errors that are syntactically correct but logically invalid.',
            self::TOO_MANY_REQUESTS => 'Use for rate limiting, preventing spam or excessive API calls.',
            self::INTERNAL_SERVER_ERROR => 'Use for unexpected server errors, unhandled exceptions, or database connection failures.',
            self::NOT_IMPLEMENTED => 'Use for features that are planned but not yet implemented in the API.',
            self::BAD_GATEWAY => 'Use when external services return invalid responses.',
            self::SERVICE_UNAVAILABLE => 'Use when the server is down for maintenance, overloaded, or temporarily unavailable.',
            self::GATEWAY_TIMEOUT => 'Use when external services timeout.',
        };
    }

    public function isSuccess(): bool
    {
        return $this->value >= 200 && $this->value < 300;
    }

    public function isClientError(): bool
    {
        return $this->value >= 400 && $this->value < 500;
    }

    public function isServerError(): bool
    {
        return $this->value >= 500 && $this->value < 600;
    }

    public static function getAllCodes(): array
    {
        return [
            'success' => [
                self::OK,
                self::CREATED,
                self::ACCEPTED,
                self::NO_CONTENT,
            ],
            'client_errors' => [
                self::BAD_REQUEST,
                self::UNAUTHORIZED,
                self::FORBIDDEN,
                self::NOT_FOUND,
                self::METHOD_NOT_ALLOWED,
                self::NOT_ACCEPTABLE,
                self::CONFLICT,
                self::UNPROCESSABLE_ENTITY,
                self::TOO_MANY_REQUESTS,
            ],
            'server_errors' => [
                self::INTERNAL_SERVER_ERROR,
                self::NOT_IMPLEMENTED,
                self::BAD_GATEWAY,
                self::SERVICE_UNAVAILABLE,
                self::GATEWAY_TIMEOUT,
            ],
        ];
    }

    public function getInfo(): array
    {
        return [
            'code' => $this->value,
            'message' => $this->message(),
            'description' => $this->description(),
            'use_case' => $this->useCase(),
            'is_success' => $this->isSuccess(),
            'is_client_error' => $this->isClientError(),
            'is_server_error' => $this->isServerError(),
        ];
    }
}
