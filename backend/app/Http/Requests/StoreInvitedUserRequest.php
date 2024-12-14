<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInvitedUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Customize this if you need to limit access to certain users
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            
            'user_email' => 'required|email|unique:invited_users,user_email',
            'invitable_id' => 'required|integer',
            'invitable_type' => [
                'required',
                'string',
                Rule::in(['App\Models\Project', 'App\Models\Team']), // Adjust with your actual models
            ],
            
        ];
    }

    /**
     * Customize the error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'token.required' => 'A unique token is required for each invitation.',
            'expires_at.required' => 'An expiration date is required for the invitation.',
            'user_email.required' => 'An email address is required for the invited user.',
            'invitable_id.required' => 'An ID for the associated project or team is required.',
            'invitable_type.required' => 'The type of entity (project or team) must be specified.'
            
        ];
    }
}
