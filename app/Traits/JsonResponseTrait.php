<?php

namespace App\Traits;

trait JsonResponseTrait
{
    public function formatJsonResponse($message, $data, $meta, $status)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'meta' => $meta
        ], $status);
    }
}
