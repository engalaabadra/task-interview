<?php

namespace App\Http\Requests\File;

 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UploadFileRequest extends FormRequest
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
            'file' => 'required|file|mimes:jpg,png,pdf,doc|max:2048'
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
