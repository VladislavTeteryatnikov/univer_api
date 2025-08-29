<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function sendResponse($data, string $message = 'ok', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function sendError(string $messageError, int $statusCode = 404): JsonResponse
    {
        return response()->json([
            'status_code' => $statusCode,
            'message' => $messageError,
            'data' => null,
        ], $statusCode);
    }
}
