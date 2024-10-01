<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentActionRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow access
    }

    public function rules()
    {
        return [
            // Additional validation rules can go here if needed for approving/rejecting
        ];
    }

    public function messages()
    {
        return [
            // Define custom error messages if required
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     * @return void
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new \Illuminate\Validation\ValidationException($validator, errorResponse('Validation failed', 422, $validator->errors()));
    }
}
