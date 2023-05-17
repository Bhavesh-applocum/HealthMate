<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminClientRequest extends FormRequest
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
            //avatar with extension and file size
            'avatar' => 'mimes:jpg,png,jpeg|max:2048',
            'practice_name' => 'required|string|min:2|max:50',
            'email' => 'required|email|unique:clients,email|unique:candidates,email',
            'phone' => 'required|numeric|digits_between:10,12',
            'address' => 'required|string|min:2|max:50',
            'area' => 'required|string|min:2|max:50',
            'post_code' => 'required|numeric|digits_between:4,7',
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
            'avatar.mimes' => 'Avatar must be a file of type: jpg, png, jpeg.',
            'avatar.max' => 'Avatar size must be a less then 2MB',
            'practice_name.required' => 'Practice name is required.',
            'practice_name.string' => 'Practice name must be a string.',
            'practice_name.min' => 'Practice name must be at least 2 characters.',
            'practice_name.max' => 'Practice name may not be greater than 50 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'Email has already been taken.',
            'phone.required' => 'Phone is required.',
            'phone.numeric' => 'Phone must be a number.',
            'phone.digits_between' => 'Phone must be between 10 and 12 digits.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a string.',
            'address.min' => 'Address must be at least 2 characters.',
            'address.max' => 'Address may not be greater than 50 characters.',
            'area.required' => 'Area is required.',
            'area.string' => 'Area must be a string.',
            'area.min' => 'Area must be at least 2 characters.',
            'area.max' => 'Area may not be greater than 50 characters.',
            'post_code.required' => 'Post code is required.',
            'post_code.numeric' => 'Post code must be a number.',
            'post_code.digits_between' => 'Post code must be between 4 and 7 digits.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.regex' => 'Password must contain at least one lowercase letter, one uppercase letter, one digit and one special character.',
            'confirm_password.same' => 'Confirm password must match password.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $message = config('params.msg_error') . $validator->errors()->first() . config('params.msg_end');
        $this->session()->flash('message', $message);
        parent::failedValidation($validator);
    }
}
