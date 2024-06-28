<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;

class CustomValidationException extends ValidationException
{
    public function render($request)
    {
        $errors = $this->validator->errors()->getMessages();
        $formattedErrors = [];

        foreach ($errors as $field => $messages) {
            foreach ($messages as $message) {
                $formattedErrors[] = [
                    'code' => 'ERR007',
                    'status' => 'error',
                    'message' => $message,
                ];
            }
        }

        return response()->json([
            'response' => $formattedErrors[0],  // Assume you only need the first error message for simplicity
        ], 422);
    }
}
