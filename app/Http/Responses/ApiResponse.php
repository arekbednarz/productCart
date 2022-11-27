<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Validation\Validator;

class ApiResponse
{
    public static function validationError(Validator $validator) {
        return response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 400);
    }

    public static function success(string $message, mixed $data = null, bool $success = true) {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, 200);
    }
}
