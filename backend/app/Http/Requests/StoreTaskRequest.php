<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'string', 'in:low,medium,high'],
            'due_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('validation.required', ['attribute' => 'title']),
            'title.string' => trans('validation.string', ['attribute' => 'title']),
            'title.max' => trans('validation.max.string', ['attribute' => 'title', 'max' => 255]),
            'description.string' => trans('validation.string', ['attribute' => 'description']),
            'status.required' => trans('validation.required', ['attribute' => 'status']),
            'status.string' => trans('validation.string', ['attribute' => 'status']),
            'status.in' => trans('validation.in', ['attribute' => 'status']),
            'priority.required' => trans('validation.required', ['attribute' => 'priority']),
            'priority.string' => trans('validation.string', ['attribute' => 'priority']),
            'priority.in' => trans('validation.in', ['attribute' => 'priority']),
            'due_date.required' => trans('validation.required', ['attribute' => 'due date']),
            'due_date.date' => trans('validation.date', ['attribute' => 'due date']),
        ];
    }
}
