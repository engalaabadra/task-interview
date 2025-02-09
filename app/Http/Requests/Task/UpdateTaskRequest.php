<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseRequest;

class UpdateTaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title' => ['required', 'string', 'unique:tasks', Rule::unique(\App\Models\Task::class)->ignore($this->id)],
            'description' => ['required', 'string'],
            'status' => ['in:pending,in_progress,completed'],
            'due_date' => ['nullable'],
            //  // Validation for translations
            //  'translations' => 'nullable|array',
            //  'translations.*.lang' => 'required|string|distinct|in:ar,en,fr', // Prevent duplicate languages
            //  'translations.*.title' => 'required|string|max:255',
            //  'translations.*.description' => 'required|string',
            'active' => ['sometimes', 'in:1,0'],
        ];

        $rules = $this->dynamicTranslationRules( $rules, \App\Models\Task::$translationFields);
        return $rules;
    }
}