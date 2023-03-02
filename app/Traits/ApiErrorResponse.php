<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait ApiErrorResponse
{
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'failed',
            // 'message' => 'Validation Error',
            'message' => 'Please fill all the required fields',
            'errors' => $validator->errors()
        ]));
        // return response()->json([
        //     'message' => 'password is incorrect',
        //     'status' => 'Bad Request',
        //     'code' => 400
        // ], 400);
    }
}
