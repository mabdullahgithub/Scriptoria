<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use App\Traits\ApiExceptionHandler;

class BaseApiController extends Controller
{
    use ApiResponses, ApiExceptionHandler;

    protected $version = 'v1';

    protected function getApiVersion(): string
    {
        return $this->version;
    }

    protected function getResourceIdentifier(string $resource, int $id): string
    {
        return sprintf('/api/%s/%s/%d', $this->version, $resource, $id);
    }
}
