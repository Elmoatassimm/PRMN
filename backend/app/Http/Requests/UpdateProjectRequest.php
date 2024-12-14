<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Project;

class UpdateProjectRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', 'string', 'in:not_started,in_progress,on_hold,completed,cancelled'],
            'start_date' => ['sometimes', 'required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_date'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('validation.required', ['attribute' => __('attributes.name')]),
            'name.string' => __('validation.string', ['attribute' => __('attributes.name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('attributes.name'), 'max' => 255]),
            'description.string' => __('validation.string', ['attribute' => __('attributes.description')]),
            'status.required' => __('validation.required', ['attribute' => __('attributes.status')]),
            'status.in' => __('validation.in', ['attribute' => __('attributes.status')]),
            'start_date.required' => __('validation.required', ['attribute' => __('attributes.start_date')]),
            'start_date.date' => __('validation.date', ['attribute' => __('attributes.start_date')]),
            'end_date.date' => __('validation.date', ['attribute' => __('attributes.end_date')]),
            'end_date.after_or_equal' => __('validation.after_or_equal', ['attribute' => __('attributes.end_date'), 'date' => __('attributes.start_date')]),
        ];
    }
}
