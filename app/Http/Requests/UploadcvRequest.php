<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiErrorResponse;

class UploadcvRequest extends FormRequest
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
            'cv' => 'mimes:pdf,doc,docx,jpg,xls,xlsx|max:2048',
        ];
    }
}
