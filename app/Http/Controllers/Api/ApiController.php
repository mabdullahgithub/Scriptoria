<?php

namespace App\Http\Controllers\Api;

use App\Enums\HttpStatus;

class ApiController extends BaseApiController
{
    public function index()
    {
        return $this->handleApiCall(function () {
            $apiInfo = [
                'name' => 'Scriptoria CMS API',
                'version' => 'v1',
                'description' => 'A RESTful API for the Scriptoria Content Management System',
                'endpoints' => [
                    'articles' => [
                        'GET /api/v1/articles' => 'Get all published articles',
                        'GET /api/v1/articles/{id}' => 'Get a specific published article'
                    ]
                ],
                'documentation' => [
                    'base_url' => request()->getSchemeAndHttpHost() . '/api/v1',
                    'authentication' => 'None required for public endpoints',
                    'rate_limiting' => 'Standard rate limits apply'
                ]
            ];

            return $this->successResponse(
                $apiInfo,
                'API information retrieved successfully',
                null,
                HttpStatus::OK->value
            );
        });
    }
}
