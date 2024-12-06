<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class StoreTeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'project_id' => 'required|exists:projects,id',
            
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => trans('validation.required', ['attribute' => 'name']),
            'name.string' => trans('validation.string', ['attribute' => 'name']),
            'name.max' => trans('validation.max.string', ['attribute' => 'name', 'max' => 255]),
            'project_id.required' => trans('validation.required', ['attribute' => 'project']),
            'project_id.exists' => trans('validation.exists', ['attribute' => 'project']),
        ];
    }
}
