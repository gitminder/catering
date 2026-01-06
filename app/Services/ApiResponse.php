<?php
// app/Services/ApiResponse.php
namespace App\Services;

class ApiResponse
{
    public static function validationError($errors, string $message = 'Invalid request data')
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => \App\Enums\ApiErrorCode::VALIDATION_ERROR,
                'message' => $message,
                'details' => $errors,
            ]
        ], 422);
    }
}
