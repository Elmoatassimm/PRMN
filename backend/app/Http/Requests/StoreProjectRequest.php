<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('validation.required', ['attribute' => trans('attributes.name')]),
            'start_date.required' => trans('validation.required', ['attribute' => trans('attributes.start_date')]),
            'end_date.after_or_equal' => trans('validation.after_or_equal', ['attribute' => trans('attributes.end_date'), 'date' => trans('attributes.start_date')]),
        ];
    }
}
