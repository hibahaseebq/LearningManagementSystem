<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuizAssignmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'quiz_id' => 'required|exists:quizzes,id',
            'user_ids' => 'required|array|min:1',  // Ensure that user_ids is an array with at least 1 user
            'user_ids.*' => 'exists:users,id',     // Ensure each user ID exists in the 'users' table
            'assigned_at' => 'required|date',
            'activation_date' => 'required|date',
            'expiration_date' => 'required|date|after:activation_date',
        ];
    }

    public function messages()
    {
        return [
            'quiz_id.required' => 'The quiz ID is required',
            'quiz_id.exists' => 'The selected quiz does not exist',
            'user_ids.required' => 'You must select at least one user',
            'user_ids.array' => 'The users must be an array',
            'user_ids.min' => 'You must select at least one user', // New message for min users validation
            'user_ids.*.exists' => 'One or more selected users are invalid',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        // Throwing a JSON response with validation errors
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
