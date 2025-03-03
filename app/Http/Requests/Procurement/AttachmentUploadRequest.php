<?php

namespace App\Http\Requests\Procurement;

use Illuminate\Foundation\Http\FormRequest;

class AttachmentUploadRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'document_file' => ['required','mimes:jpeg,jpg,png,pdf,doc,docx,txt,xls,xlsx,csv,ppt,pptx,','max:10240'],
        ];
    }

    public function messages()
    {
        return [
            'document_file.required' => 'Please upload file.',
            'document_file.max' => 'The file size must not exceed 10MB.',
            'document_file.mimes' => 'Only JPEG, JPG, PNG, and PDF files are allowed.'
        ];
    }
}
