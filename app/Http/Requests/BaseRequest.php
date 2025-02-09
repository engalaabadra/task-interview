<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Dynamic Translation Rules in request file
     * @param array $rules
     * @param array $translationFields
     * @return array $rules
     */
    public function dynamicTranslationRules($rules, $translationFields){
        $rules['lang'] = [
            'string',
            'in:' . implode(',', supportedLanguages()),
        ];

        // Add dynamic validation rules for each field in the translations
        foreach ($translationFields as $field) {
            $rules["translations.*.$field"] = 'required|string|max:255';
        }
        return $rules;
    }
}
