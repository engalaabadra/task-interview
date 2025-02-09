<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\BaseRequest;

class StoreTaskRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /* Example request
        {
            "lang": "ar",
            "status": "pending",
            "due_date" : "2025-02-28 19:09:32"
            "translations": [
            {
                "lang": "en",
                "title": "task1",
                "description": "task1"
            },
            {
                "lang": "fr",
                "title": "Nom de l'enseignant",
                "description": "Nom de l'enseignant2"

            }
            ]
        }
      */
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'title' => ['required', 'string', 'unique:tasks'],
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
    
    
    
    /**
     * @return array
     */
    public function messages()
    {
        return [
        
        ];
    }

}