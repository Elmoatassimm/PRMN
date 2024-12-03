<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // Optionally, add authorization logic here
        // e.g., return auth()->user()->can('update', Project::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|json|nullable',
            'status' => 'sometimes|json',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date|nullable',
        ];
    }

    /**
     * Customize the validation messages.
     */
    public function messages()
    {
        return [
            'name.string' => 'The project name must be a string.',
            'description.json' => 'The description must be in JSON format.',
            'status.json' => 'The status must be in JSON format.',
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
        ];
    }
}
