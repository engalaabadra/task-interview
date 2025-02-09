<?php

namespace App\Http\Requests\File;

 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UploadFilesRequest extends FormRequest
{
     

    /**
     * Determine if the Post is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:jpg,png,pdf,doc|max:2048', // Validate each file
            
        ];
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
