<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminCreateJobRequest extends FormRequest
{
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'client_id' => 'required|integer',
            'role' => 'required|string|max:255',
            'parking' => 'required|string|max:255',
            'jobdate' => 'required',
            'job_start_time' => 'required',
            'job_end_time' => 'required',
            'break' => 'required',
            'salary' => 'required|integer',
            'address_id' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Job title is required',
            'description.required' => 'Job description is required',
            'client_id.required' => 'Client is required',
            'category.required' => 'Job category is required',
            'parking.required' => 'Parking is required',
            'start_date.required' => 'Start date is required',
            'end_date.required' => 'End date is required',
            'start_time.required' => 'Start time is required',
            'end_time.required' => 'End time is required',
            'break_time.required' => 'Break time is required',
            'salary.required' => 'Salary is required',
            'address_id.required' => 'Address is required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = config('params.msg_error') . $validator->errors()->first() . config('params.msg_end');
        $this->session()->flash('message', $message);
        parent::failedValidation($validator);
    }
}
