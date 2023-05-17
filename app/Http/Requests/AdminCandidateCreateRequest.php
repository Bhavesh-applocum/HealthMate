<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminCandidateCreateRequest extends FormRequest
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
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'avatar' => 'nullable|mimes:jpeg,png,jpg|max:2048',
            'email' => 'required|string|email|max:255|unique:candidates,email|unique:clients,email',
            'phone' => 'required|numeric|digits_between:10,10',
            'gender' => 'required',
            'role' => 'required|integer',
            'password' => [
                'required',
                'string',
                'min:8',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
            'confirm_password' => 'same:password', 
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.string' => 'First name must be a string',
            'first_name.min' => 'First name must be at least 2 characters',
            'first_name.max' => 'First name may not be greater than 50 characters',
            'last_name.required' => 'Last name is required',
            'last_name.string' => 'Last name must be a string',
            'last_name.min' => 'Last name must be at least 2 characters',
            'last_name.max' => 'Last name may not be greater than 50 characters',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, png, jpg.',
            'avatar.max' => 'Avatar size must be a less then 2MB',
            'email.required' => 'Email is required.',
            'email.string' => 'Email must be a string.',
            'email.email' => 'Enter valid email address.',
            'email.max' => 'Email may not be greater than 255 characters.',
            'email.unique' => 'Email has already been taken.',
            'phone.required' => 'Phone is required.',
            'phone.numeric' => 'Phone must be a number.',
            'phone.digits_between' => 'Phone must be 10 digits.',
            'gender.required' => 'Gender is required.',
            'role.required' => 'Role is required.',
            'role.integer' => 'Role must be a number.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit and one special character.',
            'confirm_password.same' => 'Confirm password and password must be same.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = config('params.msg_error') . $validator->errors()->first() . config('params.msg_end');
        $this->session()->flash('message', $message);
        parent::failedValidation($validator);
    }
}
