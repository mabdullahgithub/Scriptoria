<?php

namespace App\Traits;

trait ApiResponses
{
    protected function successResponse($data, $message = null, $token = null, $code = 200)
    {
        $response = [
            'code' => $code,
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ];

        if ($token) {   
            $response['token'] = $token;
        }

        return response()->json($response, $code);
    }

    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'code' => $code,
            'status' => 'Error',
            'message' => $message,
            'data' => null
        ], $code);
    }
}
