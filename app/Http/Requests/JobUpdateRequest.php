<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiErrorResponse;
use Dotenv\Validator;

class JobUpdateRequest extends FormRequest
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
            'job_title' => 'required',
            'job_type' => 'required',
            'job_description' => 'required',
            'job_category' => 'required',
            'job_salary' => 'required',
            'job_location' => 'required',
            'job_start_date' => 'required',
            'job_end_date' => 'required',
            'job_status' => 'required',
            'job_start_time' => 'required',
            'job_end_time' => 'required',
            'break_time' => 'required',
        ];
    }
}
