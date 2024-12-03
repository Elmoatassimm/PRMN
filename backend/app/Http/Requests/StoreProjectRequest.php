<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // You can add authorization logic here if needed
        return true; // Allow all users for this example
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|json|max:255',
            'description' => 'nullable|json',
            
            'start_date'  => 'required|date|date_format:Y-m-d',
            'end_date'    => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',

        ];
    }

    /**
     * Get the validation error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'The project name is required.',
            'status.required' => 'The project status is required.',
            'start_date.required' => 'The project start date is required.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            // Add any other custom messages as necessary
        ];
    }
}
