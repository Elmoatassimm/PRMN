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
            'status' => [ 'string', 'in:pending,in_progress,completed'],
            'priority' => ['required', 'string', 'in:low,medium,high'],
            'due_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => trans('validation.required', ['attribute' => trans('attributes.title')]),
            'title.string' => trans('validation.string', ['attribute' => trans('attributes.title')]),
            'title.max' => trans('validation.max.string', ['attribute' => trans('attributes.title'), 'max' => 255]),
            'description.string' => trans('validation.string', ['attribute' => trans('attributes.description')]),
            'status.required' => trans('validation.required', ['attribute' => trans('attributes.status')]),
            'status.string' => trans('validation.string', ['attribute' => trans('attributes.status')]),
            'status.in' => trans('validation.in', ['attribute' => trans('attributes.status')]),
            'priority.required' => trans('validation.required', ['attribute' => trans('attributes.priority')]),
            'priority.string' => trans('validation.string', ['attribute' => trans('attributes.priority')]),
            'priority.in' => trans('validation.in', ['attribute' => trans('attributes.priority')]),
            'due_date.required' => trans('validation.required', ['attribute' => trans('attributes.due_date')]),
            'due_date.date' => trans('validation.date', ['attribute' => trans('attributes.due_date')]),
        ];
    }
}
