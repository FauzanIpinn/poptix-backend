<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ApiResponse
{
    protected function success(string $message, JsonResource|ResourceCollection|array|null $data = null, int $status = 200): JsonResponse
    {
        return response()->json(array_filter([
            'message' => $message,
            'data'    => $data,
        ], fn ($v) => $v !== null), $status);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        return response()->json(array_filter([
            'message' => $message,
            'errors'  => $errors ?: null,
        ], fn ($v) => $v !== null), $status);
    }
}