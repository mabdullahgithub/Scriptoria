<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponses;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersion
{
    use ApiResponses;

    public function handle(Request $request, Closure $next, $version = 'v1'): Response
    {
        $supportedVersions = ['v1'];
        
        if (!in_array($version, $supportedVersions)) {
            return $this->errorResponse(
                400,
                "API version '{$version}' is not supported. Supported versions: " . implode(', ', $supportedVersions)
            );
        }

        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-API-Version', $version);
        $request->merge(['api_version' => $version]);
        
        $response = $next($request);
        
        if ($response instanceof Response) {
            $response->headers->set('X-API-Version', $version);
            $response->headers->set('Content-Type', 'application/json');
        }
        
        return $response;
    }
}
