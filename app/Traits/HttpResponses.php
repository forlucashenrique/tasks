<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\MessageBag;

trait HttpResponses
{
    public function response(string $message, string|int $status, array|Model|JsonResource $data = [])
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'data' => $data,
        ], $status, [], JSON_UNESCAPED_SLASHES);
    }

    public function error(string $message, string|int $status, array|MessageBag $errors)
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
            'errors' => $errors,
        ], $status, [], JSON_UNESCAPED_SLASHES);
    }
}
