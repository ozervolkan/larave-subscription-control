<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class JsonResponseHelper
{
    /**
     * @param array $data
     * @param string|null $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function success(array $data, string $message = null, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function error(string $message, int $statusCode = 500): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => $message,
        ], $statusCode);
    }


}
