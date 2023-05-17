<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminCandidateUpdateRequest extends FormRequest
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
            'first_name' => 'required|string|min:4|max:255',
            'last_name' => 'required|string|min:4|max:255',
            'email' => 'required|email|unique:clients,email|unique:candidates,email,' . $this->id,
            'phone' => 'required|numeric|digits_between:10,10',
            'avatar' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.string' => 'First name must be string',
            'first_name.min' => 'First name must be at least 4 characters',
            'first_name.max' => 'First name must be less than 255 characters',
            'last_name.required' => 'Last name is required',
            'last_name.string' => 'Last name must be string',
            'last_name.min' => 'Last name must be at least 4 characters',
            'last_name.max' => 'Last name must be less than 255 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be valid email',
            'email.unique' => 'Email already exists',
            'phone.required' => 'Phone is required',
            'phone.numeric' => 'Phone must be numeric',
            'phone.digits_between' => 'Phone must be between 10 digits',
            'avatar.mimes' => 'Avatar must be jpeg, png, jpg',
            'avatar.max' => 'Avatar must be less than 2048 kb',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = config('params.msg_error') . $validator->errors()->first() . config('params.msg_end');
        $this->session()->flash('message', $message);
        parent::failedValidation($validator);
    }
}
