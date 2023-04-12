<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiErrorResponse;

class CandidateUpdateRequest extends FormRequest
{

    use ApiErrorResponse;
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cv'    => 'mimes:.csv, .txt, .xlx, .xls, .pdf'|'size:2 MB',
            'avatar' => 'mimes:jpg,png,jpeg,svg,webp',
            'email' => 'email',
            'phone' => 'numeric|digits:10',
            'password' => [
                'string',
                'min:8',             // must be at least 10 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'same:password',
        ];
    }
}
