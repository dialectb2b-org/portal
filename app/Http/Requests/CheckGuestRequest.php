<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Models\Company;
use App\Models\CompanyDocument;

class CheckGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
    **/
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
            'name' => ['required','max:255'],
            'email' => ['required','max:255','email']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please choose your email.',
        ];
    }
}
