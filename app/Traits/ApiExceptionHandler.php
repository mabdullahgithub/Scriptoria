<?php

namespace App\Traits;

use Throwable;

trait ApiExceptionHandler
{
    use ApiResponses;

    public function handleApiCall(callable $callback)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
