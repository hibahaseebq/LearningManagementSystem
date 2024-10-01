<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,manager,supervisor,student',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name is required.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.min' => 'The password must be at least 8 characters long.',
            'role.required' => 'The role is required.',
            'role.in' => 'The role must be one of: admin, manager, supervisor, student.',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();
        throw new \Illuminate\Validation\ValidationException($validator, response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422));
    }
}
