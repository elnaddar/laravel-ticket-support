<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * @return JsonResponse
     * @param mixed $message
     * @param mixed $data
     */
    protected function ok($message, $data = []): JsonResponse
    {
        return $this->success($message, $data, 200);
    }
    /**
     * @return JsonResponse
     * @param mixed $message
     * @param mixed $data
     */
    protected function success($message, $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json([
            "message" => $message,
            "status" => $statusCode,
            "data" => $data
        ], $statusCode);
    }
    /**
     * @return JsonResponse
     * @param mixed $message
     */
    protected function error($message, int $statusCode): JsonResponse
    {
        return response()->json([
            "message" => $message,
            "status" => $statusCode,
        ], $statusCode);
    }
}
//
